<?php
/**
 * PrestaChamps
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Commercial License
 * you can't distribute, modify or sell this code
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file
 * If you need help please contact leo@prestachamps.com
 *
 * @author    Mailchimp
 * @copyright Mailchimp
 * @license   commercial
 */

namespace PrestaChamps\Queue;
if (!defined('_PS_VERSION_')) {
    exit;
}
use Db;
use PrestaChamps\Queue\Exceptions\InvalidJobException;

use PrestaChamps\Queue\Jobs\CartRuleSyncJob;
use PrestaChamps\Queue\Jobs\CartSyncJob;
use PrestaChamps\Queue\Jobs\CustomerSyncJob;
use PrestaChamps\Queue\Jobs\NewsletterSubscriberSyncJob;
use PrestaChamps\Queue\Jobs\OrderSyncJob;
use PrestaChamps\Queue\Jobs\ProductSyncJob;

class Queue
{
    //protected $table = "ps_mailchimppro_jobs";
	protected $table = _DB_PREFIX_ . "mailchimppro_jobs";
	protected $max_try;

    const STATUS_AVAILABLE = 0;
    const STATUS_PROCESSED = 100;
    const STATUS_PROCESSING = 10;
    const STATUS_FAILED = -1;
    const PRIORITIES_BY_TYPE = array (
        "product"                => 1,
        "customer"               => 2,
        "cartRule"               => 3,
        "order"                  => 4,
        "cart"                   => 5,
        "newsletterSubscriber"   => 6
	);
	
	public function __construct()
	{
		$this->max_try = ($max = (int)\Configuration::get(\MailchimpProConfig::QUEUE_ATTEMPT)) && $max > 0 && \Validate::isUnsignedInt($max) ? $max : 2;
		$this->max_jobs = ($limit = (int)\Configuration::get(\MailchimpProConfig::QUEUE_STEP)) && $limit > 0 && \Validate::isUnsignedInt($limit) ? $limit : 10;
	}

    public function push(JobInterface $job, $channel, $id_shop)
    {
        $class = $this->escape($job->getJobType());

        $bodyJson = $job->convertToArrayJson();

        $body = Db::getInstance()->escape($bodyJson);

        if (!$entityId = $job->getJobId()) {
            $entityId = 0;
        }
		if (!$priority = self::PRIORITIES_BY_TYPE[$class]) {
			$priority = 1;
		}
        Db::getInstance()->execute(
            "INSERT IGNORE INTO {$this->table} (`channel`, `body`, `type`, `id_entity`, `priority`, `id_shop`) VALUES ('{$channel}', '{$body}', '{$class}', '{$entityId}', '{$priority}', '{$id_shop}')"
        );
    }

    protected function escape($value)
    {
        $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
        $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");

        return str_replace($search, $replace, $value);
    }

    public function getNumberOfAvailableJobs($channel = "%%")
    {
    	$idShop = \Context::getContext()->shop->id;

        $status = pSQL(self::STATUS_AVAILABLE);
        $channel = pSQL($channel);
        return (int)Db::getInstance()->getValue("select COUNT(*) from {$this->table} where `status` = {$status} and `channel` LIKE '{$channel}' and `id_shop` = {$idShop}");
    }
	
	public function getNumberOfTotalJobs($channel = "%%")
    {
    	$idShop = \Context::getContext()->shop->id;

        return (int)Db::getInstance()->getValue("select COUNT(*) from {$this->table} where `id_shop` = {$idShop}");
    }

    public function getNumberOfAvailableJobsPerType($channel = "%%")
    {
    	$idShop = \Context::getContext()->shop->id;
        // SELECT class, count(*) FROM ps_mailchimppro_jobs GROUP BY class;
        $status = pSQL(self::STATUS_AVAILABLE);
        return Db::getInstance()->query("select type, COUNT(*) as count from {$this->table} where status = {$status} and channel LIKE '{$channel}' and `id_shop` = {$idShop} group by type")->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function getNumberOfJobsInFlight($channel = "%%")
    {
    	$idShop = \Context::getContext()->shop->id;
        $status = pSQL(self::STATUS_PROCESSING);
        $channel = pSQL($channel);
        return (int)Db::getInstance()->getValue("select COUNT(*) from {$this->table} where `status` = {$status} and `channel` LIKE '{$channel}' and `id_shop` = {$idShop}");
    }

    protected function reserveJob($jobId)
    {
        //$status = self::STATUS_AVAILABLE;
		$status = pSQL(self::STATUS_PROCESSING);
		$jobId = pSQL($jobId);
        return Db::getInstance()->execute("update {$this->table} set `status` = {$status}, `locked_at` = CURRENT_TIMESTAMP where `id_job` = {$jobId}");
    }

    protected function unReserveJob($jobId, $message = "Unknown error")
    {
        $status = pSQL(self::STATUS_AVAILABLE);
        $message = pSQL($message);
        $jobId = pSQL($jobId);
        return Db::getInstance()->execute("update {$this->table} set `status` = {$status}, `error` = '{$message}' where `id_job` = {$jobId}");
    }

    protected function deleteJob($jobId)
    {
        $jobId = pSQL($jobId);
        return Db::getInstance()->execute("delete from {$this->table} where `id_job` = {$jobId}");
    }

    protected function deleteUnsuccessfulJobs()
    {
		$max_try = pSQL($this->max_try);
        return Db::getInstance()->execute("delete from {$this->table} where `attempts` >= {$max_try}");
    }

    public function clearChannel($channel = "%%")
    {
    	$idShop = \Context::getContext()->shop->id;
        $channel = pSQL($channel);
        return Db::getInstance()->execute("delete from {$this->table} where `channel` LIKE '{$channel}' and `id_shop` = {$idShop}");
    }

    /* protected function failJob($jobId, $message = "Unknown error")
    {
        $status = pSQL(self::STATUS_AVAILABLE);
        $jobId = pSQL($jobId);
        $message = pSQL($message);

        return Db::getInstance()->execute("update {$this->table} set `status` = {$status}, `error` = '{$message}' where `id_job` = {$jobId}");
    } */

    protected function increaseAttemptsCounter($jobId)
    {
        $jobId = pSQL($jobId);
        return Db::getInstance()->execute("update {$this->table} set `attempts` = `attempts` + 1 where `id_job` = {$jobId}");
    }
	
	protected function getAttemptsCounter($jobId)
    {
        $jobId = pSQL($jobId);
        return Db::getInstance()->getValue("select `attempts` from {$this->table} where `id_job` = {$jobId}");
    }

    public function pop($channel = "%%", $limit = false)
    {
    	$idShop = \Context::getContext()->shop->id;
        $channel = pSQL($channel);
        $status_available = pSQL(self::STATUS_AVAILABLE);
        $status_processing = pSQL(self::STATUS_PROCESSING);
		$max_try = pSQL($this->max_try);
		if (!$limit) {
			$result = Db::getInstance()->getRow("select* from {$this->table} where ((`status` = {$status_available} and `attempts` < " . (int)$max_try . ") OR (`status` = {$status_processing} AND TIMESTAMPDIFF(SECOND, `locked_at`, CURRENT_TIMESTAMP) > 60)) and `channel` LIKE '{$channel}' and `id_shop` = {$idShop} order by `priority` ASC, `id_entity` ASC");

		}
		else {
			$result = Db::getInstance()->query("select* from {$this->table} where ((`status` = {$status_available} and `attempts` < " . (int)$max_try . ") OR (`status` = {$status_processing} AND TIMESTAMPDIFF(SECOND, `locked_at`, CURRENT_TIMESTAMP) > 60)) and `channel` LIKE '{$channel}' and `id_shop` = {$idShop} order by `priority` ASC, `id_entity` ASC LIMIT " . (int)$limit)->fetchAll();
		}
		

        if (empty($result)) {
            return null;
        }
		
		if (!$limit) {
			$this->increaseAttemptsCounter($result['id_job']);
		}
		else {
			foreach ($result as $queue) {
				/* $this->reserveJob($queue['id_job']); */
				//dump($queue);//die('sad');
				$this->increaseAttemptsCounter($queue['id_job']);
			}
		}

        return $result;
    }

    /**
     * @throws InvalidJobException
     */
    public function runJob($channel = "%%")
    {
        $errorMessage = '';
        $requestSuccess = true;
		$jobType = '';

        $jobData = $this->pop($channel);

        if (empty($jobData)) {
            $this->deleteUnsuccessfulJobs();
			return [
				'success' => true,
				'message' => 'No job to sync!',
				'type' => null,
				'data' => null
			];
        }

        try {

        	$job = $this->getJobObject($jobData["type"], $jobData["body"]);

            $this->reserveJob($jobData['id_job']);

            if (!($job instanceof JobInterface)) {
                $resp = "Deserialized object is not an instance of " . JobInterface::class;
                $this->unReserveJob($jobData['id_job'], $resp);
                throw new InvalidJobException($resp);
            }

            // $response = $job->execute();
            $response = $job->execute($jobData['id_shop']);
            
            if (isset($response['requestSuccess']) && $response['requestSuccess'] == true) {
                $this->deleteJob($jobData['id_job']);
                $requestSuccess = true;
				$jobType = $job->getJobType();
            }
            else {
                $requestSuccess = false;
				if (isset($response['requestLastErrors'])) {
                    if (is_array($response['requestLastErrors'])) {
                        $errorMessage = implode(";", array_values($response['requestLastErrors']));
                    }
                    else {
                        $errorMessage = $response['requestLastErrors'];
                    }
					if ($attempsCount = $this->getAttemptsCounter($jobData['id_job'])) {
						$errorMessage .= ' (' . $attempsCount . '. attemp(s))';
					}
					$this->unReserveJob($jobData['id_job'], $errorMessage);
                }
				else {
					$this->unReserveJob($jobData['id_job']);
				}
			}
			$this->deleteUnsuccessfulJobs();
        } catch (\Exception $exception) {
            $this->unReserveJob($jobData['id_job'], $exception->getMessage());
            return [
                'success' => false,
                'message' => $exception->getMessage(),
                'type' => null,
                'data' => null,
            ];
        }

        return [
            'success' => $requestSuccess,
            'message' => $errorMessage,
            'type' => get_class($job),
            'data' => $jobData,
			'jobType' => $jobType
        ];
    }
	
	public function addSpecificPriceProductJobs(){

		$current_date = new \DateTime(null, new \DateTimeZone(@date_default_timezone_get()));

		$sql = "SELECT * FROM " . _DB_PREFIX_ . "mailchimppro_specific_price WHERE (`needToRun` = 2 AND `start_date` < '" . $current_date->format('Y-m-d H:i:s') ."') OR (`needToRun` = 1 AND `end_date` < '" . $current_date->format('Y-m-d H:i:s') ."')";

		$specific_prices = Db::getInstance()->executeS($sql);

		if(count($specific_prices) > 0){
			foreach($specific_prices as $specific_price){

				$queue = new Queue();
                $job = new Jobs\ProductSyncJob();
                $job->productId = $specific_price["id_product"];
                $queue->push($job, 'setup-wizard', $specific_price["id_shop"]);

				if ($specific_price["needToRun"] == 1) {
					// dump("delete");
					$delSql = "DELETE FROM " . _DB_PREFIX_ . "mailchimppro_specific_price WHERE id_specific_price = " .(int)$specific_price["id_specific_price"];
                    $delete = Db::getInstance()->execute($delSql);
                }

				if ($specific_price["needToRun"] == 2) {
					// dump("update");
					$updSql = "UPDATE " . _DB_PREFIX_ . "mailchimppro_specific_price SET `needToRun` = 1 WHERE id_specific_price = " .(int)$specific_price["id_specific_price"];
                    $update = Db::getInstance()->execute($updSql);
                }


			}
		}

	}
	
	
	public function runCronjob($channel = "%%")
    {
		//dump(\Configuration::get(\MailchimpProConfig::CRONJOB_SECURE_TOKEN));die();
		//\Configuration::updateGlobalValue('MAILCHIMPPRO_IS_CRONJOB_RUNNING', false, true);
		//die('asdss');
		//$is_cronjob_running = \Configuration::getGlobalValue('MAILCHIMPPRO_IS_CRONJOB_RUNNING');
		/* if (!$is_cronjob_running) { */
			\Configuration::updateValue(\MailchimpProConfig::LAST_CRONJOB, date('Y-m-d H:i:s'), true);
			\Configuration::updateGlobalValue('MAILCHIMPPRO_IS_CRONJOB_RUNNING', true, true);
			
			//$startTime = date('Y-m-d H:i:s');
			$startTime = new \DateTime();

			if (!($token = trim(\Tools::getValue('secure'))) || !\Validate::isCleanHtml($token) || $token != \Configuration::get(\MailchimpProConfig::CRONJOB_SECURE_TOKEN)) {
				if (\Tools::isSubmit('ajax')) {
					die(json_encode(array(
						'errors' => 'Access denied',
						'result' => ''
					)));
				}
				die('Access denied');
			}

			$this->addSpecificPriceProductJobs();
			
			$count = 0;
			$errors = array();
			
			$max_try = $this->max_try ? $this->max_try : 2;
			$max_jobs = $this->max_jobs? $this->max_jobs : 10;
			
			//$jobData = $this->pop();
			//dump($jobData);die();
			
			$status = self::STATUS_AVAILABLE;

			if ($queues = $this->pop($channel, $max_jobs)) {
				// reserve all the selected jobs
				foreach ($queues as $queue) {
					$this->reserveJob($queue['id_job']);
					//dump($queue);//die('sad');
				}
				
				//dump($queues);die();
				foreach ($queues as $queue) {
					$errorMessage = '';
					$requestSuccess = true;
					
					//$this->increaseAttemptsCounter($queue['id_job']);

        			$job = $this->getJobObject($queue["type"], $queue["body"]);

					if (!($job instanceof JobInterface)) {
						//throw new InvalidJobException("Deserialized object is not an instance of " . JobInterface::class);
						$resp = "Deserialized object is not an instance of " . JobInterface::class;
						$this->unReserveJob($queue['id_job'], $resp);
					}
				
				
				
			//$execution = $job->execute();
			

					
				   // EtsRVMailLog::writeLog($queue, EtsRVMailLog::SEND_MAIL_TIMEOUT);
					//if (($execution = $job->execute()) || (int)Db::getInstance()->getValue('SELECT `attempts` FROM `' . $this->table . '` WHERE `id_job` = ' . (int)$queue['id_job']) > $max_try) {
					
					$response = $job->execute($queue['id_shop']); // parameter is store ID from PS multi store configuration
					
					
					if (isset($response['requestSuccess']) && $response['requestSuccess'] == true) {
						$count++;
						$this->deleteJob($queue['id_job']);
					}
					else {
						$requestSuccess = false;
						if (isset($response['requestLastErrors'])) {
							if (is_array($response['requestLastErrors'])) {
								$errorMessage = implode(";", array_values($response['requestLastErrors']));
							}
							else {
								$errorMessage = $response['requestLastErrors'];
							}
							if ($attempsCount = $this->getAttemptsCounter($queue['id_job'])) {
								$errorMessage .= ' (' . $attempsCount . '. attemp(s))';
							}
							
							if (!empty($response['requestLastResponse']['body'])) {
								$responseBody = json_decode($response['requestLastResponse']['body'], true);
								if (!empty($responseBody['errors']) && is_array($responseBody['errors'])) {
									$additionalErrors = array();
									foreach ($responseBody['errors'] as $bodyErrors) {
										if (!empty($bodyErrors['message'])) {
											$additionalErrors[] = $bodyErrors['message'];
										}
									}
									if (!empty($additionalErrors)) {
										$errorMessage .= "\n";
										$errorMessage .= "**********";
										$errorMessage .= "\n • " . implode("\n • ", $additionalErrors) . "\n";
										$errorMessage .= "**********";
										$errorMessage .= "\n";
									}
								}
							}
							
							$this->unReserveJob($queue['id_job'], $errorMessage);
							$errors[] = '− Job type: ' . $job->getJobType() . ' | Id: ' . $queue['id_entity'] . ' | Message: ' . $errorMessage;
						}
						else {
							$this->unReserveJob($queue['id_job']);
						}
					}
					
					/* if ($execution = $job->execute()) {	
						//EtsRVMailLog::writeLog($queue, EtsRVMailLog::SEND_MAIL_DELIVERED);
						if ($execution) {
							//Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'ets_rv_tracking` SET `delivered` = 1, date_upd=\'' . pSQL(date('Y-m-d H:i:s')) . '\' WHERE `queue_id` = ' . (int)$queue['id_ets_rv_email_queue']);
							$count++;
							//dump($execution);die('sasd');
						}
						//Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'ets_rv_email_queue` WHERE `id_ets_rv_email_queue` = ' . (int)$queue['id_ets_rv_email_queue']);
						$this->deleteJob($queue['id_job']);
					} else {
						//EtsRVMailLog::writeLog($queue, EtsRVMailLog::SEND_MAIL_FAILED);
						//Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'ets_rv_email_queue` SET `sent` = 0, `send_count` = `send_count` + 1, `sending_time` = \'' . pSQL(date('Y-m-d H:i:s')) . '\' WHERE `id_ets_rv_email_queue` = ' . (int)$queue['id_ets_rv_email_queue']);
						$this->failJob($queue['id_job'], "Deserialized object is not an instance of " . JobInterface::class);
						$this->increaseAttemptsCounter($queue['id_job']);
						$this->unReserveJob($queue['id_job']);
					} */

				}
			}

			//Db::getInstance()->execute('DELETE FROM `' . $this->table . '` WHERE attempts >= ' . (int)$max_try);
			$this->deleteUnsuccessfulJobs();

			if ((int)\Configuration::get(\MailchimpProConfig::LOG_CRONJOB)) {
				$log = "================================================================\n";
				$log .= date(\Context::getContext()->language->date_format_full);
				$msg = 'There were %s job(s) successfully synced to the MailChimp!';
				if ($count)
					$log .= '  ' . sprintf($msg, $count);
				else
					$log .= '  ' . 'No job has been synced';
				
				if ($errors) {
					$log .= "\n";
					$log .= 'Errors: ';
					foreach ($errors as $error) {
						$log .= " \n " . $error;
					}
				}
				$log .= "\n";
				$dest = _PS_MODULE_DIR_ . 'mailchimppro/logs'; //. $module->name;
				if ($count || $errors) {
					if (!@is_dir($dest))
						@mkdir($dest, 0755, true);

					@file_put_contents($dest . '/cronjob.log', $log . PHP_EOL, FILE_APPEND);
					//$fileContents = @file_get_contents($dest . '/cronjob.log');
					//@file_put_contents($dest . '/cronjob.log', $log . PHP_EOL . $fileContents);
				}
			}
		
			\Configuration::updateGlobalValue('MAILCHIMPPRO_IS_CRONJOB_RUNNING', false, true);
			
			$endTime = new \DateTime();
			\Configuration::updateValue(\MailchimpProConfig::LAST_CRONJOB_EXECUTION_TIME, $startTime->diff($endTime)->format('%Y-%m-%d %H:%i:%s'), true);
			
			$jsonArr = array(
				'result' => 'Cronjob ran successfully:' . ' ' . ($count <= 0 ? 'No job has been synced!' : sprintf('%s job(s) was synced to the MailChimp!', $count)),
			);
		/* }
		else {
			$jsonArr = array(
				'result' => 'Previous cronjob still running...',
			);
		} */

        die(json_encode($jsonArr));
    }

    public function getJobObject($type,$body)
    {
    	$job = null;

    	switch($type){
			case CartRuleSyncJob::JOB_TYPE:{
				$job = new CartRuleSyncJob($body);
				break;
			}
			case CartSyncJob::JOB_TYPE:{
				$job = new CartSyncJob($body);
				break;
			}
			case CustomerSyncJob::JOB_TYPE:{
				$job = new CustomerSyncJob($body);
				break;
			}
			case NewsletterSubscriberSyncJob::JOB_TYPE:{
				$job = new NewsletterSubscriberSyncJob($body);
				break;
			}
			case OrderSyncJob::JOB_TYPE:{
				$job = new OrderSyncJob($body);
				break;
			}
			case ProductSyncJob::JOB_TYPE:{
				$job = new ProductSyncJob($body);
				break;
			}
		}

		return $job;
    }
}
