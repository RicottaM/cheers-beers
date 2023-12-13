<?php
/**
 * MailChimp
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
if (!defined('_PS_VERSION_')) {
    exit;
}
/**
 * Class Mailchimppro
 */
class Mailchimppro extends Module
{
    const MC_MIDDLEWARE = "https://prestashop.mailchimpapp.com";
    /**
     * @var \DrewM\MailChimp\MailChimp MailChimp API client object
     *
     * @see https://github.com/drewm/mailchimp-api
     */
    protected $apiClient;

    public $menus = [
        [
            'is_root' => true,
            'name' => 'Mailchimp Config',
            'class_name' => 'mailchimppro',
            'visible' => true,
            'parent_class_name' => 0,
        ],
        [
            'is_root' => false,
            'name' => 'Mailchimp Config',
            'class_name' => 'AdminMailchimpProConfig',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Mailchimp Configuration',
            'class_name' => 'AdminMailchimpProConfiguration',
            'visible' => true,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Mailchimp Setup Wizard',
            'class_name' => 'AdminMailchimpProWizard',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Queue Work',
            'class_name' => 'AdminMailchimpProQueue',
            'visible' => true,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Mailchimp List',
            'class_name' => 'AdminMailchimpProLists',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Mailchimp Batches',
            'class_name' => 'AdminMailchimpProBatches',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Mailchimp Carts',
            'class_name' => 'AdminMailchimpProCarts',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Mailchimp Customers',
            'class_name' => 'AdminMailchimpProCustomers',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Mailchimp Orders',
            'class_name' => 'AdminMailchimpProOrders',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Mailchimp Products',
            'class_name' => 'AdminMailchimpProProducts',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Mailchimp Stores',
            'class_name' => 'AdminMailchimpProStores',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Mailchimp Sync',
            'class_name' => 'AdminMailchimpProSync',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Mailchimp Sites',
            'class_name' => 'AdminMailchimpProSites',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Mailchimp Automations',
            'class_name' => 'AdminMailchimpProAutomations',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'List members',
            'class_name' => 'AdminMailchimpProListMembers',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Promo rules',
            'class_name' => 'AdminMailchimpProPromoRules',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Promo codes',
            'class_name' => 'AdminMailchimpProPromoCodes',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Email templates',
            'class_name' => 'AdminMailchimpProEmailTemplates',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Tags',
            'class_name' => 'AdminMailchimpProTags',
            'visible' => true,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Segments',
            'class_name' => 'AdminMailchimpProSegments',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Campaigns',
            'class_name' => 'AdminMailchimpProCampaigns',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
        [
            'is_root' => false,
            'name' => 'Statistics',
            'class_name' => 'AdminMailchimpProReports',
            'visible' => false,
            'parent_class_name' => 'mailchimppro',
        ],
    ];


    public function __construct()
    {
        $this->name = 'mailchimppro';
        $this->tab = 'administration';
        $this->version = '3.0.10';
        $this->author = 'Mailchimp';
        $this->need_instance = 1;
        $this->bootstrap = true;
        $this->module_key = '793ebc5f330220c7fb7b817fe0d63a92';

        parent::__construct();

        $this->displayName = 'Mailchimp';
        $this->description = 'Official Mailchimp integration for PrestaShop';
        $this->ps_versions_compliancy = ['min' => '1.6', 'max' => _PS_VERSION_];

        require_once $this->getLocalPath() . 'vendor/autoload.php';

        $this->configuration = \MailchimpProConfig::getConfigurationValues();
    }


    /**
     * Install the required tabs, configs and stuff
     *
     * @return bool
     * @throws PrestaShopException
     *
     * @throws PrestaShopDatabaseException
     * @since 0.0.1
     *
     */
    public function install()
    {
        $tabRepository = new \PrestaChamps\PrestaShop\Tab\TabRepository($this->menus, 'mailchimppro');
        $tabRepository->install();

        return parent::install() &&
            // The moduleRoutes hook is necessary in order to load the autoloader
            $this->registerHook('moduleRoutes') &&
            $this->registerHook('displayFooter') &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('actionProductUpdate') &&
            $this->registerHook('actionValidateOrder') &&
            $this->registerHook('actionObjectUpdateAfter') &&
            $this->registerHook('actionObjectDeleteAfter') &&
            $this->registerHook('actionOrderStatusUpdate') &&
            $this->registerHook('actionCartSave') &&
            $this->registerHook('actionObjectCustomerAddAfter') &&
            $this->registerHook('actionObjectCartRuleAddAfter') &&
            $this->registerHook('actionObjectCartRuleDeleteBefore') &&
            $this->registerHook('displayAdminOrderContentOrder') &&
            $this->registerHook('displayAdminOrderTabOrder') &&
            $this->registerHook('displayBackOfficeTop') &&
            $this->registerHook('actionFrontControllerSetMedia') &&
            $this->registerHook('actionObjectCartRuleUpdateAfter') &&
            $this->registerHook('displayFooterBefore') &&
            $this->registerHook('actionNewsletterRegistrationAfter') &&
            $this->registerHook('actionCustomerAccountAdd') &&
            $this->registerHook('displayAdminProductsExtra') &&
            $this->registerHook('actionCustomerAccountUpdate') &&

            $this->registerHook('actionObjectSpecificPriceAddAfter') &&
            $this->registerHook('actionObjectSpecificPriceUpdateAfter') &&
            $this->registerHook('actionObjectSpecificPriceDeleteAfter') &&

            $this->registerHook('actionObjectAddAfter') &&
            $this->registerHook('actionObjectAddressAddAfter') &&

            $this->installDb();
    }

	protected function installDb() {
		$pf = _DB_PREFIX_ . "{$this->name}_";
		return Db::getInstance()->execute("
            CREATE TABLE IF NOT EXISTS `{$pf}jobs` (
                `id_job` INT NOT NULL AUTO_INCREMENT,
                `id_entity` INT NULL,
                `type` VARCHAR(50)  NOT NULL,
                `channel` TINYTEXT  NOT NULL,
                `status` TINYINT NOT NULL DEFAULT 0,
                `attempts` SMALLINT DEFAULT 0,
                `body` MEDIUMTEXT NOT NULL,
                `error` VARCHAR(255) NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `locked_at` DATETIME NULL,
                `priority` SMALLINT DEFAULT 1,
				`id_shop` INT NOT NULL DEFAULT 1,
                PRIMARY KEY (`id_job`),
				CONSTRAINT entity_type_shop UNIQUE (id_entity,type,id_shop)
                ) ENGINE = InnoDB;
        ") &&
        Db::getInstance()->execute("
            CREATE TABLE IF NOT EXISTS `{$pf}specific_price` (
                `id_specific_price` INT NOT NULL,
                `id_product` INT NOT NULL,
                `start_date` DATETIME NOT NULL,
                `end_date` DATETIME NOT NULL,
                `needToRun` SMALLINT NOT NULL DEFAULT 0,
                `id_shop` INT NOT NULL DEFAULT 1,
                PRIMARY KEY (`id_specific_price`)
                ) ENGINE = InnoDB;
        ");
    }

    public function runUpgradeModule()
    {
        return parent::runUpgradeModule(); // TODO: Change the autogenerated stub
    }

    public function hookModuleRoutes()
    {
        return '';
    }

    /**
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function uninstall()
    {
        $tabRepository = new \PrestaChamps\PrestaShop\Tab\TabRepository($this->menus, 'mailchimppro');
        $tabRepository->uninstall();

        $pf = _DB_PREFIX_ . "{$this->name}_";
        Db::getInstance()->execute("DROP TABLE IF EXISTS `{$pf}jobs`");
        Db::getInstance()->execute("DROP TABLE IF EXISTS `{$pf}specific_price`");

        return parent::uninstall();
    }


    /**
     * Check if the current PrestaShop installation is version 1.7 or below
     *
     * @return bool
     */
    public static function isPs17()
    {
        return (bool)version_compare(_PS_VERSION_, '1.7', '>=');
    }


    /**
     * Redirect to the custom config controller
     *
     * @throws PrestaShopException
     */
    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminMailchimpProConfiguration'));
    }

    /**
     * Place UTM tracking cookie when the user arrived via MailChimp
     *
     * @param $params
     */
    public function hookDisplayHeader($params)
    {
        if ((Tools::getValue('utm_source') === 'mailchimp' || !empty(Tools::getValue('mc_cid')))
            && $this->isApiKeySet()) {
            $this->context->cookie->landing_site = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $mc_cid = Tools::getValue('mc_cid', false);
            $utm_source = Tools::getValue('utm_source', false);
            if ($mc_cid) {
                setcookie('mc_cid', Tools::getValue('mc_cid'), 0, $this->context->shop->getBaseURI());
            }
            if ($utm_source) {
                setcookie('utm_source', urldecode(Tools::getValue('utm_source')), 0, $this->context->shop->getBaseURI());
            }
            $this->context->cookie->utm_source = Tools::getValue('utm_source');
            setcookie(
                'landing_site',
                (Tools::usingSecureMode() ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
                0,
                $this->context->shop->getBaseURI()
            );
        }
    }

    /**
     * Mailchimp API client factory
     *
     * @throws Exception
     */
    public function getApiClient($idStore = null)
    {
        if(!$idStore){
            $idStore = Context::getContext()->shop->id;
        }

        if ($this->apiClient instanceof \DrewM\MailChimp\MailChimp) {

            $reflectionClass = new ReflectionClass('\DrewM\MailChimp\MailChimp');
            $reflectionProperty = $reflectionClass->getProperty('api_key');
            $reflectionProperty->setAccessible(true);

            if (Configuration::get(MailchimpProConfig::MAILCHIMP_API_KEY,null,null,$idStore) == $reflectionProperty->getValue($this->apiClient)) {
                return $this->apiClient;
            }
        }
        $this->apiClient = new \DrewM\MailChimp\MailChimp(Configuration::get(MailchimpProConfig::MAILCHIMP_API_KEY,null,null,$idStore));

        return $this->apiClient;
    }

    /**
     * @param       $url
     * @param       $method
     * @param array $data
     *
     * @return mixed
     * @throws Exception
     */
    public function sendApiRequest($url, $method, $data = [])
    {
        if ($method === 'POST') {
            $this->getApiClient()->post($url, $data);
        } elseif ($method === 'PATCH') {
            $this->getApiClient()->patch($url, $data);
        } elseif ($method === 'PUT') {
            $this->getApiClient()->put($url, $data);
        } elseif ($method === 'DELETE') {
            $this->getApiClient()->delete($url, $data);
        } else {
            $this->getApiClient()->get($url, $data);
        }

        return $this->getApiClient()->getLastResponse();
    }

    /**
     * Display site MailChimp site verification
     *
     * @param $params
     *
     * @return string
     */
    public function hookDisplayFooter($params)
    {
        if ($this->isApiKeySet() && $this->isStoreSynced()) {

            if (Configuration::get(MailchimpProConfig::SYNC_NEWSLETTER_SUBSCRIBERS)) {
                if((bool)version_compare(_PS_VERSION_, '1.7', '<')){

                    $subscriptionIsEnabled = Module::isEnabled('blocknewsletter');

                    if (Tools::isSubmit('submitNewsletter') && $subscriptionIsEnabled) {
                        try {
                        \PrestaChamps\MailchimpPro\Hooks\Display\FooterBefore::run(
                                $params,
                                $this->getApiClient(),
                                $this->context
                            )->newsletterBlockRegistration();
                        } catch (Exception $exception) {
                            PrestaShopLogger::addLog("[MAILCHIMP-NW16] :{$exception->getMessage()}");
                        }
                    }
                }
            }

            try {
                $result = $this->sendApiRequest("ecommerce/stores/{$this->getShopId()}", 'GET');
                if ($this->getApiClient()->success()) {
                    $result = json_decode($result['body'], true);

                    if (isset($result['connected_site'])) {
                        $footer = $result['connected_site']['site_script']['fragment'];
                        if (!Configuration::get(MailchimpProConfig::MAILCHIMP_SCRIPT_VERIFIED)) {
                            $site_id = $result['connected_site']['site_foreign_id'];
                            (new \PrestaChamps\MailchimpPro\Commands\SiteVerifyCommand($this->apiClient, $site_id))
                                ->execute();
                            Configuration::updateValue(MailchimpProConfig::MAILCHIMP_SCRIPT_VERIFIED, true);
                        }
                        return $footer;
                    }
                }
                PrestaShopLogger::addLog("[MAILCHIMP] :{$this->getApiClient()->getLastError()}");
            } catch (Exception $e) {
                PrestaShopLogger::addLog("[MAILCHIMP] :{$e->getMessage()}");
            }
        }
        return '';
    }

    /**
     * @param $params
     *
     * @return string
     * @throws Exception
     */
    public function hookActionFrontControllerSetMedia($params)
    {
        if ($this->isApiKeySet() && $this->isStoreSynced()) {
            try {
                if (!Configuration::get(MailchimpProConfig::MAILCHIMP_SCRIPT_VERIFIED)) {
                    $result = $this->getApiClient()->get("ecommerce/stores/{$this->getShopId()}");
                    $siteId = $result['connected_site']['site_foreign_id'];
                    $this->sendApiRequest(
                        "connected-sites/{$siteId}/actions/verify-script-installation",
                        'POST'
                    );
                    Configuration::updateValue(MailchimpProConfig::MAILCHIMP_SCRIPT_VERIFIED, true);
                }
                if (isset($result['connected_site'])) {
                    $this->context->controller->addJS($result['connected_site']['site_script']['url'], false);
                }
            } catch (Exception $exception) {
                PrestaShopLogger::addLog("[MAILCHIMP] :{$exception->getMessage()}");
            }
        }
        return '';
    }

    /**
     * Sync the newly created customer to MailChimp
     *
     * @param $params
     */
    public function hookActionObjectCustomerAddAfter($params)
    {
        if ($this->isApiKeySet() && $this->isStoreSynced()) {
            if (Configuration::get(MailchimpProConfig::SYNC_CUSTOMERS)) {
                try {
                    /**
                     * @var $customer Customer
                     */
                    $customer = $params['object'];
                    // check filter customers to sync
                    if(in_array($customer->active, $this->configuration[\MailchimpProConfig::CUSTOMER_SYNC_FILTER_ENABLED]) && 
                        in_array($customer->newsletter, $this->configuration[\MailchimpProConfig::CUSTOMER_SYNC_FILTER_NEWSLETTER])){
                        if (!Configuration::get(MailchimpProConfig::CRONJOB_BASED_SYNC)) {
                            $command = new \PrestaChamps\MailchimpPro\Commands\CustomerSyncCommand(
                                $this->context,
                                $this->getApiClient(),
                                [$customer->id]
                            );
                            $command->triggerDoubleOptIn(true);
                            $command->setSyncMode($command::SYNC_MODE_REGULAR);
                            $command->setMethod($command::SYNC_METHOD_PUT);
                            $command->execute();
                        } else {
                            $job = new \PrestaChamps\Queue\Jobs\CustomerSyncJob();
                            $job->customerId = $customer->id;
                            $job->triggerDoubleOptIn(true);
                            $job->setSyncMode(\PrestaChamps\MailchimpPro\Commands\CustomerSyncCommand::SYNC_MODE_REGULAR);
                            $job->setMethod(\PrestaChamps\MailchimpPro\Commands\CustomerSyncCommand::SYNC_METHOD_PUT);
                            $queue = new \PrestaChamps\Queue\Queue();
                            $queue->push($job, 'hook-customer-add-after', $this->context->shop->id);
                        }
                    }
                } catch (Exception $exception) {
                    $this->context->controller->errors[] = "[MAILCHIMP] :{$exception->getMessage()}";
                    PrestaShopLogger::addLog("[MAILCHIMP] :{$exception->getMessage()}");
                }
            }
        }
    }

    /**
     * Sync the newly created address for customer to MailChimp
     *
     * @param $params
     */
    public function hookActionObjectAddAfter($params)
    {
        return '';
    }

    /**
     * Sync the newly created address for customer to MailChimp
     *
     * @param $params
     */
    public function hookActionObjectAddressAddAfter($params)
    {
        if ($this->isApiKeySet() && $this->isStoreSynced()) {
            if (Configuration::get(MailchimpProConfig::SYNC_CUSTOMERS)) {
                try {
                    /**
                     * @var $address Address
                     */
                    $address = $params['object'];

                    $customer = new Customer($address->id_customer);
                    // check filter customers to sync
                    if(in_array($customer->active, $this->configuration[\MailchimpProConfig::CUSTOMER_SYNC_FILTER_ENABLED]) && 
                        in_array($customer->newsletter, $this->configuration[\MailchimpProConfig::CUSTOMER_SYNC_FILTER_NEWSLETTER])){
                        if (!Configuration::get(MailchimpProConfig::CRONJOB_BASED_SYNC)) {
                            $command = new \PrestaChamps\MailchimpPro\Commands\CustomerSyncCommand(
                                $this->context,
                                $this->getApiClient(),
                                [$address->id_customer]
                            );
                            $command->triggerDoubleOptIn(true);
                            if($this->context->controller->controller_type == 'admin'){
                                $command->setUpdateSubscriptionStatus(false);
                            }
                            $command->setSyncMode(\PrestaChamps\MailchimpPro\Commands\CustomerSyncCommand::SYNC_MODE_REGULAR);
                            $command->setMethod(\PrestaChamps\MailchimpPro\Commands\CustomerSyncCommand::SYNC_METHOD_PUT);
                            $command->execute();
                        } else {
                            $job = new \PrestaChamps\Queue\Jobs\CustomerSyncJob();
                            $job->customerId = $address->id_customer;
                            $job->triggerDoubleOptIn(true);
                            if($this->context->controller->controller_type == 'admin'){
                                $job->setUpdateSubscriptionStatus(false);
                            }
                            $job->setSyncMode(\PrestaChamps\MailchimpPro\Commands\CustomerSyncCommand::SYNC_MODE_REGULAR);
                            $job->setMethod(\PrestaChamps\MailchimpPro\Commands\CustomerSyncCommand::SYNC_METHOD_PUT);
                            $queue = new \PrestaChamps\Queue\Queue();
                            $queue->push($job, 'hook-address-add-after', $this->context->shop->id);
                        }
                    }
                } catch (Exception $exception) {
                    $this->context->controller->errors[] = "[MAILCHIMP] :{$exception->getMessage()}";
                    PrestaShopLogger::addLog("[MAILCHIMP] :{$exception->getMessage()}");
                }
            }
        }
    }

    /**
     * @param $params
     *
     * @throws Exception
     * @todo Refactor code to use a service pattern
     *
     */
    public function hookActionObjectCartRuleAddAfter($params)
    {
        if ($this->isApiKeySet() && $this->isStoreSynced()) {
            if (Configuration::get(MailchimpProConfig::SYNC_CART_RULES)) {
                $object = new CartRule($params['object']->id, $this->context->language->id);
                if (!Configuration::get(MailchimpProConfig::CRONJOB_BASED_SYNC)) {
                    $command = new \PrestaChamps\MailchimpPro\Commands\CartRuleSyncCommand(
                        $this->context,
                        $this->getApiClient(),
                        [$object]
                    );
                    $command->setSyncMode($command::SYNC_MODE_REGULAR);
                    $command->setMethod($command::SYNC_METHOD_POST);
                    $command->execute();
                } else {
                    $job = new \PrestaChamps\Queue\Jobs\CartRuleSyncJob();
                    $job->cartRuleId = $object->id;
                    $job->setSyncMode(\PrestaChamps\MailchimpPro\Commands\CartRuleSyncCommand::SYNC_MODE_REGULAR);
                    $job->setMethod(\PrestaChamps\MailchimpPro\Commands\CartRuleSyncCommand::SYNC_METHOD_POST);
                    $queue = new \PrestaChamps\Queue\Queue();
                    $queue->push($job, 'hook-cartrule-add-after', $this->context->shop->id);
                }
            }
        }
    }

    /**
     * @param $params
     *
     * @throws Exception
     * @todo Refactor code to use a service pattern
     *
     */
    public function hookActionObjectCartRuleUpdateAfter($params)
    {
        if ($this->isApiKeySet() && $this->isStoreSynced()) {
            if (Configuration::get(MailchimpProConfig::SYNC_CART_RULES)) {
                $object = new CartRule($params['object']->id, $this->context->language->id);
                if (!Configuration::get(MailchimpProConfig::CRONJOB_BASED_SYNC)) {
                    $command = new \PrestaChamps\MailchimpPro\Commands\CartRuleSyncCommand(
                        $this->context,
                        $this->getApiClient(),
                        [$object]
                    );
                    $command->setSyncMode($command::SYNC_MODE_REGULAR);
                    $command->setMethod($command::SYNC_METHOD_PATCH);
                    $command->execute();
                } else {
                    $job = new \PrestaChamps\Queue\Jobs\CartRuleSyncJob();
                    $job->cartRuleId = $object->id;
                    $job->setSyncMode(\PrestaChamps\MailchimpPro\Commands\CartRuleSyncCommand::SYNC_MODE_REGULAR);
                    $job->setMethod(\PrestaChamps\MailchimpPro\Commands\CartRuleSyncCommand::SYNC_METHOD_PATCH);
                    $queue = new \PrestaChamps\Queue\Queue();
                    $queue->push($job, 'hook-cartrule-update-after', $this->context->shop->id);
                }
            }
        }
    }

    /**
     * @param $params
     *
     * @throws Exception
     * @todo Refactor code to use a service pattern
     *
     */
    public function hookActionObjectCartRuleDeleteBefore($params)
    {
        if ($this->isApiKeySet() && $this->isStoreSynced()) {
            if (Configuration::get(MailchimpProConfig::SYNC_CART_RULES)) {
                $object = new CartRule($params['object']->id, $this->context->language->id);
                if (!Configuration::get(MailchimpProConfig::CRONJOB_BASED_SYNC)) {
                    $command = new \PrestaChamps\MailchimpPro\Commands\CartRuleSyncCommand(
                        $this->context,
                        $this->getApiClient(),
                        [$object]
                    );
                    $command->setSyncMode($command::SYNC_MODE_REGULAR);
                    $command->setMethod($command::SYNC_METHOD_DELETE);
                    $command->execute();
                } else {
                    $job = new \PrestaChamps\Queue\Jobs\CartRuleSyncJob();
                    $job->cartRuleId = $object->id;
                    $job->setSyncMode(\PrestaChamps\MailchimpPro\Commands\CartRuleSyncCommand::SYNC_MODE_REGULAR);
                    $job->setMethod(\PrestaChamps\MailchimpPro\Commands\CartRuleSyncCommand::SYNC_METHOD_DELETE);
                    $queue = new \PrestaChamps\Queue\Queue();
                    $queue->push($job, 'hook-cartrule-delete-before', $this->context->shop->id);
                }
            }
        }
    }


    /**
     * Create or update the cart in Mailchimp
     *
     * @param $params
     *
     * @throws Exception
     * @todo Use command pattern instead
     *
     */
    public function hookActionCartSave($params)
    {
        if ($this->isApiKeySet() && $this->isStoreSynced() && (Tools::getValue('controller') !== 'adminaddresses')) {
            if (Configuration::get(MailchimpProConfig::SYNC_CARTS)) {
                try {
                    \PrestaChamps\MailchimpPro\Hooks\Action\CartSave::run(
                        $this->context,
                        $this->getApiClient()
                    );
                } catch (Exception $exception) {
                    $this->context->controller->errors[] = "[MAILCHIMP] :{$exception->getMessage()}";
                    PrestaShopLogger::addLog("[MAILCHIMP] :{$exception->getMessage()}");
                }
            }
        }
    }

    /**
     * Sync the order status update to MailChimp
     *
     * @param $params
     */
    public function hookActionOrderStatusUpdate($params)
    {
        if ($this->isApiKeySet() && $this->isStoreSynced()) {
            if (Configuration::get(MailchimpProConfig::SYNC_ORDERS)) {
                try {
                    $orderId = null;
                    if (isset($params['id_order'])) {
                        $orderId = $params['id_order'];
                    }
                    if (isset($params['newOrderStatus']) && isset($params['newOrderStatus'], $params['newOrderStatus']->id_order)) {
                        $orderId = $params['newOrderStatus']->id_order;
                    }
                    if ($orderId) {
                        $order = new \Order($orderId, $this->context->language->id);

                        if(in_array($order->getCustomer()->active, $this->configuration[\MailchimpProConfig::CUSTOMER_SYNC_FILTER_ENABLED]) &&
                            in_array($order->getCustomer()->newsletter, $this->configuration[\MailchimpProConfig::CUSTOMER_SYNC_FILTER_NEWSLETTER])){
                            if (!Configuration::get(MailchimpProConfig::CRONJOB_BASED_SYNC)) {
                                $command = new \PrestaChamps\MailchimpPro\Commands\OrderSyncCommand(
                                    $this->context,
                                    $this->getApiClient(),
                                    [$orderId]
                                );
                                $command->setSyncMode($command::SYNC_MODE_REGULAR);
                                $command->setMethod(
                                    $command->getOrderExists($orderId)
                                        ? $command::SYNC_METHOD_PATCH
                                        : $command::SYNC_METHOD_POST
                                );
                                $command->execute();
                            } else {
                                $job = new \PrestaChamps\Queue\Jobs\OrderSyncJob();
                                $job->orderId = $orderId;
                                $job->setSyncMode(\PrestaChamps\MailchimpPro\Commands\OrderSyncCommand::SYNC_MODE_REGULAR);
                                if (isset($_COOKIE['mc_cid']) && !empty($_COOKIE['mc_cid']) && !is_a($this->context->controller, 'AdminController') && !is_subclass_of($this->context->controller, 'AdminController')) {
                                    $job->setCampaignId($_COOKIE['mc_cid']);
                                }
                                $queue = new \PrestaChamps\Queue\Queue();
                                $queue->push($job, 'hook-order-status-update', $this->context->shop->id);
                            }
                        }
                    }
                } catch (Exception $exception) {
                    $this->context->controller->errors[] = "[MAILCHIMP] :{$exception->getMessage()}";
                    PrestaShopLogger::addLog("[MAILCHIMP] :{$exception->getMessage()}");
                }
            }
        }
    }

    public function hookActionValidateOrder($params)
    {
        if (isset($params['order']) && is_subclass_of($params['order'], 'OrderCore') && $this->isApiKeySet() && $this->isStoreSynced()) {
            if (Configuration::get(MailchimpProConfig::SYNC_ORDERS)) {
                try {
                    $order = new Order($params['order']->id, $this->context->language->id);

                    if(in_array($order->getCustomer()->active, $this->configuration[\MailchimpProConfig::CUSTOMER_SYNC_FILTER_ENABLED]) &&
                        in_array($order->getCustomer()->newsletter, $this->configuration[\MailchimpProConfig::CUSTOMER_SYNC_FILTER_NEWSLETTER])){

                        if (!Configuration::get(MailchimpProConfig::CRONJOB_BASED_SYNC)) {
                            $command = new \PrestaChamps\MailchimpPro\Commands\OrderSyncCommand(
                                $this->context,
                                $this->getApiClient(),
                                [$params['order']->id]
                            );
                            $command->setSyncMode($command::SYNC_MODE_REGULAR);
                            $command->setMethod(
                                $command->getOrderExists($params['order']->id)
                                    ? $command::SYNC_METHOD_PATCH
                                    : $command::SYNC_METHOD_POST
                            );
                            $command->execute();

                            $command = new \PrestaChamps\MailchimpPro\Commands\CartSyncCommand(
                                $this->context,
                                $this->getApiClient(),
                                [$order->id_cart]
                            );
                            $command->setSyncMode($command::SYNC_MODE_REGULAR);
                            $command->setMethod($command::SYNC_METHOD_DELETE);
                            $command->execute();

                            /* $this->sendApiRequest(
                                "ecommerce/stores/{$this->getShopId()}/carts/{$order->id_cart}",
                                'DELETE'
                            ); */
                        } else {
                            $job = new \PrestaChamps\Queue\Jobs\OrderSyncJob();
                            $job->orderId = $params['order']->id;
                            $job->setSyncMode(\PrestaChamps\MailchimpPro\Commands\OrderSyncCommand::SYNC_MODE_REGULAR);
                            if (isset($_COOKIE['mc_cid']) && !empty($_COOKIE['mc_cid']) && !is_a($this->context->controller, 'AdminController') && !is_subclass_of($this->context->controller, 'AdminController')) {
                                $job->setCampaignId($_COOKIE['mc_cid']);
                            }
                            $queue = new \PrestaChamps\Queue\Queue();
                            $queue->push($job, 'hook-action-validate-order', $this->context->shop->id);

                            $job = new \PrestaChamps\Queue\Jobs\CartSyncJob();
                            $job->cartId = $order->id_cart;
                            $job->setSyncMode(\PrestaChamps\MailchimpPro\Commands\CartSyncCommand::SYNC_MODE_REGULAR);
                            $job->setMethod(\PrestaChamps\MailchimpPro\Commands\CartSyncCommand::SYNC_METHOD_DELETE);

                            $queue->push($job, 'hook-action-validate-order', $this->context->shop->id);
                        }
                    }
                } catch (Exception $exception) {
                    $this->context->controller->errors[] = "[MAILCHIMP] :{$exception->getMessage()}";
                    PrestaShopLogger::addLog("[MAILCHIMP] :{$exception->getMessage()}");
                }
            }
        }
    }

    /**
     * Delete the objects from the MailChimp account also
     *
     * @param $params
     */
    public function hookActionProductUpdate($params)
    {
        if (isset($params['product']) && $this->isApiKeySet() && $this->isStoreSynced()) {
            if (Configuration::get(MailchimpProConfig::SYNC_PRODUCTS)) {
                try {
                    $product = $params['product'];
                    if (is_a($product, 'ProductCore')) {
                        /**
                         * @var $product Product
                         */
                        if (!Configuration::get(MailchimpProConfig::CRONJOB_BASED_SYNC)) {
                            $command = new \PrestaChamps\MailchimpPro\Commands\ProductSyncCommand(
                                $this->context,
                                $this->getApiClient(),
                                [$product->id]
                            );
                            $command->setSyncMode($command::SYNC_MODE_REGULAR);
                            $command->setMethod(
                                $command->getProductExists($product->id)
                                    ? $command::SYNC_METHOD_PATCH
                                    : $command::SYNC_METHOD_POST
                            );
                            $command->execute();
                        } else {
                            $job = new \PrestaChamps\Queue\Jobs\ProductSyncJob();
                            $job->productId = $product->id;
                            $job->setSyncMode(\PrestaChamps\MailchimpPro\Commands\ProductSyncCommand::SYNC_MODE_REGULAR);
                            $queue = new \PrestaChamps\Queue\Queue();
                            $queue->push($job, 'hook-product-update', $this->context->shop->id);
                        }
                    }
                } catch (Exception $exception) {
                    $this->context->controller->errors[] = $exception->getMessage();
                    PrestaShopLogger::addLog(
                        "MAILCHIMP_ERROR: {$exception->getMessage()}",
                        1,
                        $exception->getCode(),
                        PrestaChamps\MailchimpPro\Commands\ProductSyncCommand::class,
                        null,
                        true
                    );
                }
            }
        }
    }

    /**
     * Delete the objects from the MailChimp account also
     *
     * @param $object
     */
    public function hookActionObjectDeleteAfter($object)
    {
        if ($this->isApiKeySet() && $this->isStoreSynced()) {
            if (is_subclass_of($object['object'], 'ProductCore')) {
                if (Configuration::get(MailchimpProConfig::SYNC_PRODUCTS)) {
                    try {
                        $objectId = $object['object']->id;
                        if (!Configuration::get(MailchimpProConfig::CRONJOB_BASED_SYNC)) {
                            //$this->getApiClient()->delete("ecommerce/stores/{$this->getShopId()}/products/{$objectId}");
                            $command = new \PrestaChamps\MailchimpPro\Commands\ProductSyncCommand(
                                $this->context,
                                $this->getApiClient(),
                                [$objectId]
                            );
                            $command->setSyncMode($command::SYNC_MODE_REGULAR);
                            $command->setMethod($command::SYNC_METHOD_DELETE);
                            $command->execute();
                        } else {
                            $job = new \PrestaChamps\Queue\Jobs\ProductSyncJob();
                            $job->productId = $objectId;
                            $job->setSyncMode(\PrestaChamps\MailchimpPro\Commands\ProductSyncCommand::SYNC_MODE_REGULAR);
                            $job->setMethod(\PrestaChamps\MailchimpPro\Commands\ProductSyncCommand::SYNC_METHOD_DELETE);
                            $queue = new \PrestaChamps\Queue\Queue();
                            $queue->push($job, 'hook-object-delete-after', $this->context->shop->id);
                        }
                    } catch (Exception $e) {
                        $this->context->controller->errors[] = "[MAILCHIMP] :{$e->getMessage()}";
                        PrestaShopLogger::addLog("[MAILCHIMP] :{$e->getMessage()}");
                    }
                }
            }
            elseif (is_subclass_of($object['object'], 'AddressCore')) {
                if (Configuration::get(MailchimpProConfig::SYNC_CUSTOMERS)) {
                    try {
                        $customerId = $object['object']->id_customer;
                        if ($customerId) {
                            $customer = new Customer($customerId);
                            // check filter customers to sync
                            if(in_array($customer->active, $this->configuration[\MailchimpProConfig::CUSTOMER_SYNC_FILTER_ENABLED]) && 
                                in_array($customer->newsletter, $this->configuration[\MailchimpProConfig::CUSTOMER_SYNC_FILTER_NEWSLETTER])){
                                if (!Configuration::get(MailchimpProConfig::CRONJOB_BASED_SYNC)) {
                                    $command = new \PrestaChamps\MailchimpPro\Commands\CustomerSyncCommand(
                                        $this->context,
                                        $this->getApiClient(),
                                        [$customerId]
                                    );
                                    if($this->context->controller->controller_type == 'admin'){
                                        $command->setUpdateSubscriptionStatus(false);
                                    }
                                    $command->setSyncMode($command::SYNC_MODE_REGULAR);
                                    $command->setMethod($command::SYNC_METHOD_PUT);
                                    $command->execute();
                                } else {
                                    $job = new \PrestaChamps\Queue\Jobs\CustomerSyncJob();
                                    $job->customerId = $customerId;
                                    if($this->context->controller->controller_type == 'admin'){
                                        $job->setUpdateSubscriptionStatus(false);
                                    }
                                    $job->setSyncMode(\PrestaChamps\MailchimpPro\Commands\CustomerSyncCommand::SYNC_MODE_REGULAR);
                                    $job->setMethod(\PrestaChamps\MailchimpPro\Commands\CustomerSyncCommand::SYNC_METHOD_PUT);
                                    $queue = new \PrestaChamps\Queue\Queue();
                                    $queue->push($job, 'hook-customer-add-after', $this->context->shop->id);
                                }
                            }
                        }
                    } catch (Exception $exception) {
                        $this->context->controller->errors[] = $exception->getMessage();
                        PrestaShopLogger::addLog(
                            "[MAILCHIMP]: {$exception->getMessage()}",
                            1,
                            $exception->getCode(),
                            PrestaChamps\MailchimpPro\Commands\CustomerSyncCommand::class,
                            null,
                            true
                        );
                    }
                }
            }
        }
    }

    /**
     * Sync the object updates to Mailchimp
     *
     * @param $object
     */
    public function hookActionObjectUpdateAfter($object)
    {
        if ($this->isApiKeySet() && $this->isStoreSynced()) {
            if (Configuration::get(MailchimpProConfig::SYNC_CUSTOMERS)) {
                if (is_subclass_of($object['object'], 'CustomerCore') || is_subclass_of($object['object'], 'AddressCore')) {
                    try {
                        if (is_subclass_of($object['object'], 'CustomerCore')) {
                            $customerId = $object['object']->id;
                        } else if (is_subclass_of($object['object'], 'AddressCore')) {
                            $customerId = $object['object']->id_customer;
                        }
                        if (isset($customerId) && $customerId) {
                            $customer = new Customer($customerId);
                            // check filter customers to sync
                            if(in_array($customer->active, $this->configuration[\MailchimpProConfig::CUSTOMER_SYNC_FILTER_ENABLED]) && 
                                in_array($customer->newsletter, $this->configuration[\MailchimpProConfig::CUSTOMER_SYNC_FILTER_NEWSLETTER])){
                                if (!Configuration::get(MailchimpProConfig::CRONJOB_BASED_SYNC)) {
                                    $command = new \PrestaChamps\MailchimpPro\Commands\CustomerSyncCommand(
                                        $this->context,
                                        $this->getApiClient(),
                                        [$customerId]
                                    );
                                    if($this->context->controller->controller_type == 'admin'){
                                        $command->setUpdateSubscriptionStatus(false);
                                    }
                                    $command->setSyncMode(\PrestaChamps\MailchimpPro\Commands\CustomerSyncCommand::SYNC_MODE_REGULAR);
                                    $command->setMethod(\PrestaChamps\MailchimpPro\Commands\CustomerSyncCommand::SYNC_METHOD_PUT);
                                    $command->execute();
                                } else {
                                    $job = new \PrestaChamps\Queue\Jobs\CustomerSyncJob();
                                    $job->customerId = $customerId;
                                    if($this->context->controller->controller_type == 'admin'){
                                        $job->setUpdateSubscriptionStatus(false);
                                    }
                                    $job->setSyncMode(\PrestaChamps\MailchimpPro\Commands\CustomerSyncCommand::SYNC_MODE_REGULAR);
                                    $job->setMethod(\PrestaChamps\MailchimpPro\Commands\CustomerSyncCommand::SYNC_METHOD_PUT);
                                    $queue = new \PrestaChamps\Queue\Queue();
                                    $queue->push($job, 'hook-object-update-after', $this->context->shop->id);
                                }
                            }
                        }
                    } catch (Exception $exception) {
                        $this->context->controller->errors[] = $exception->getMessage();
                        PrestaShopLogger::addLog(
                            "[MAILCHIMP]: {$exception->getMessage()}",
                            1,
                            $exception->getCode(),
                            PrestaChamps\MailchimpPro\Commands\CustomerSyncCommand::class,
                            null,
                            true
                        );
                    }
                }
            }

            if (is_subclass_of($object['object'], 'ShopCore')) {
                try {
                    $command = new \PrestaChamps\MailchimpPro\Commands\StoreSyncCommand(
                        $this->context,
                        $this->getApiClient(),
                        [$object['object']->id]
                    );
                    $command->setSyncMode($command::SYNC_MODE_REGULAR);
                    $command->setMethod(
                        $command->getStoreExists(static::shopIdTransformer($object['object']))
                            ? $command::SYNC_METHOD_PATCH
                            : $command::SYNC_METHOD_POST
                    );
                    $command->execute();
                } catch (Exception $exception) {
                    $this->context->controller->errors[] = $exception->getMessage();
                    PrestaShopLogger::addLog(
                        "[MAILCHIMP]: {$exception->getMessage()}",
                        1,
                        $exception->getCode(),
                        \PrestaChamps\MailchimpPro\Commands\StoreSyncCommand::class,
                        null,
                        true
                    );
                }
            }
        }
    }

    /**
     * @param $params
     *
     * @return string
     */
    public function hookDisplayAdminOrderContentOrder($params)
    {
        if ($this->isApiKeySet() && $this->isStoreSynced()) {
            try {
                /**
                 * @var $order Order
                 */
                $order = $params['order'];
				$shop = new Shop($order->id_shop);
				$idShop = static::shopIdTransformer($shop);
                //$response = $this->getApiClient()->get("ecommerce/stores/{$order->id_shop}/orders/{$order->id}");
				$response = $this->getApiClient()->get("ecommerce/stores/{$idShop}/orders/{$order->id}");
                if ($this->getApiClient()->success()) {
                    $this->context->smarty->assign([
                        'order' => $response,
                    ]);
                    return $this->context->smarty->fetch(
                        $this->getLocalPath() . 'views/templates/admin/mc-order-detail-tab-content.tpl'
                    );
                }

                return $this->context->smarty->fetch(
                    $this->getLocalPath() . 'views/templates/admin/mc-order-detail-tab-content-empty.tpl'
                );

            } catch (Exception $exception) {
                $this->context->controller->errors[] =
                    $this->l("Unable to fetch MailChimp order: {$exception->getMessage()}");
            }
        }
        return '';
    }

    /**
     * @param $params
     *
     * @return string
     * @throws SmartyException
     */
    public function hookDisplayAdminOrderTabOrder($params)
    {
        return $this->context->smarty->fetch(
            $this->getLocalPath() . '/views/templates/admin/mc-order-detail-tab-title.tpl'
        );
    }

    /**
     * @throws SmartyException
     */
    public function hookDisplayBackOfficeTop()
    {
        if ($this->context->controller->controller_name === 'AdminCarts' &&
            $this->isApiKeySet() &&
            $this->isStoreSynced()) {
            $cart = new Cart(Tools::getValue('id_cart'));
			$shop = new Shop($cart->id_shop);
			$idShop = static::shopIdTransformer($shop);
			$response = $this->getApiClient()->get("ecommerce/stores/{$idShop}/carts/{$cart->id}");

            if ($this->getApiClient()->success()) {
                $this->context->smarty->assign([
                    'cart' => $response,
                ]);
                $this->context->controller->content .=
                    $this->context->smarty->fetch(
                        $this->getLocalPath() . 'views/templates/admin/mc-cart-detail.tpl'
                    );
            }
        }
    }

    public function hookDisplayFooterBefore($params)
    {
        if ($this->isApiKeySet() && $this->isStoreSynced()) {
            if (Configuration::get(MailchimpProConfig::SYNC_NEWSLETTER_SUBSCRIBERS)) {
                try {
                    \PrestaChamps\MailchimpPro\Hooks\Display\FooterBefore::run(
                        $params,
                        $this->getApiClient(),
                        $this->context
                    )->newsletterBlockRegistration();
                } catch (Exception $exception) {
                    PrestaShopLogger::addLog("[MAILCHIMP] :{$exception->getMessage()}");
                }
            }
        }
    }

    public function hookActionNewsletterRegistrationAfter($params)
    {
        if ($this->isApiKeySet() && $this->isStoreSynced()) {
            if (Configuration::get(MailchimpProConfig::SYNC_NEWSLETTER_SUBSCRIBERS)) {
                try {
                    /*\PrestaChamps\MailchimpPro\Hooks\Action\NewsletterRegistrationAfter::run(
                        $params,
                        $this->getApiClient(),
                        $this->context,
                        $params['email']
                    )->newsletterBlockRegistration(); */
                    $newsletterSubscriber['email'] = $params['email'];
                    if (isset($this->context->language->id) && $this->context->language->id) {
                        $newsletterSubscriber['id_lang'] = $this->context->language->id;
                    }
                    if (!Configuration::get(MailchimpProConfig::CRONJOB_BASED_SYNC)) {
                        $command = new \PrestaChamps\MailchimpPro\Commands\NewsletterSubscriberSyncCommand(
                            $this->context,
                            $this->getApiClient(),
                            [$newsletterSubscriber]
                        );
                        $command->setSyncMode($command::SYNC_MODE_REGULAR);
                        $command->setMethod($command::SYNC_METHOD_PUT);
                        $command->execute();
                    } else {
                        $job = new \PrestaChamps\Queue\Jobs\NewsletterSubscriberSyncJob();
                        $job->newsletterSubscriber = $newsletterSubscriber;
                        $job->setSyncMode(\PrestaChamps\MailchimpPro\Commands\NewsletterSubscriberSyncCommand::SYNC_MODE_REGULAR);
                        $job->setMethod(\PrestaChamps\MailchimpPro\Commands\NewsletterSubscriberSyncCommand::SYNC_METHOD_PUT);
                        $queue = new \PrestaChamps\Queue\Queue();
                        $queue->push($job, 'hook-newsletter-registration-after', $this->context->shop->id);
                    }
                } catch (Exception $exception) {
                    PrestaShopLogger::addLog("[MAILCHIMP] :{$exception->getMessage()}");
                }
            }
        }
    }

    protected function isApiKeySet()
    {
        return !empty(Configuration::get(MailchimpProConfig::MAILCHIMP_API_KEY));
    }

    protected function isStoreSynced()
    {
        return !empty(Configuration::get(MailchimpProConfig::MAILCHIMP_LIST_ID)) && !empty(Configuration::get(MailchimpProConfig::MAILCHIMP_STORE_SYNCED));
    }

    public function hookActionCustomerAccountAdd($params)
    {
        if ($this->isApiKeySet() && $this->isStoreSynced()) {
            if (Configuration::get(MailchimpProConfig::SYNC_CUSTOMERS)) {
                try {
                    $customer = $this->getCustomerFromHookParam($params);
                    // check filter customers to sync
                    if(in_array($customer->active, $this->configuration[\MailchimpProConfig::CUSTOMER_SYNC_FILTER_ENABLED]) && 
                        in_array($customer->newsletter, $this->configuration[\MailchimpProConfig::CUSTOMER_SYNC_FILTER_NEWSLETTER])){
                        \PrestaChamps\MailchimpPro\Hooks\Action\Customer\AccountAdd::run(
                            $this->context,
                            $this->getApiClient(),
                            $customer
                        );
                    }
                } catch (Exception $exception) {
                    PrestaShopLogger::addLog("[MAILCHIMP] :{$exception->getMessage()}");
                }
            }
        }
    }

    public function hookActionCustomerAccountUpdate($params)
    {
        if ($this->isApiKeySet() && $this->isStoreSynced()) {
            if (Configuration::get(MailchimpProConfig::SYNC_CUSTOMERS)) {
                try {
                    $customer = $this->getCustomerFromHookParam($params);
                    // check filter customers to sync
                    if(in_array($customer->active, $this->configuration[\MailchimpProConfig::CUSTOMER_SYNC_FILTER_ENABLED]) && 
                        in_array($customer->newsletter, $this->configuration[\MailchimpProConfig::CUSTOMER_SYNC_FILTER_NEWSLETTER])){
                        \PrestaChamps\MailchimpPro\Hooks\Action\Customer\AccountUpdate::run(
                            $this->context,
                            $this->getApiClient(),
                            $customer
                        );
                    }
                } catch (Exception $exception) {
                    PrestaShopLogger::addLog("[MAILCHIMP] :{$exception->getMessage()}");
                }
            }
        }
    }

    /**
     * @param $hookParams
     *
     * @return Customer
     * @throws Exception
     */
    private function getCustomerFromHookParam($hookParams)
    {
        if (isset($hookParams['customer']) && $hookParams['customer'] instanceof CustomerCore) {
            return $hookParams['customer'];
        }

        if (isset($hookParams['newCustomer']) && $hookParams['newCustomer'] instanceof CustomerCore) {
            return $hookParams['newCustomer'];
        }

        throw new Exception("Can't get Customer from hook");
    }

    /**
     * @param $params
     *
     * @return string
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function hookDisplayAdminProductsExtra($params)
    {
        if ($this->isApiKeySet() && $this->isStoreSynced()) {
            if (Configuration::get(MailchimpProConfig::SYNC_PRODUCTS)) {
                $productId = isset($params['id_product']) ? $params['id_product'] : Tools::getValue('id_product');
                if (Validate::isLoadedObject(new Product($productId))) {
                    $this->context->smarty->assign([
                        'productId' => $productId,
                        'regenerateLink' => $this->context->link->getAdminLink('AdminMailchimpProConfiguration'),
                    ]);

                    return $this->display(__FILE__, 'views/templates/hook/admin/_products-extra.tpl');
                }
            }
        }

        return "";
    }

    public function hookActionObjectSpecificPriceAddAfter($params){
        if ($this->isApiKeySet() && $this->isStoreSynced()) {
            if (Configuration::get(MailchimpProConfig::SYNC_PRODUCTS)) {
                try {
                    if (is_subclass_of($params['object'], 'SpecificPriceCore')) {
                        $product_id = $params['object']->id_product;
                        $specific_price_id = $params['object']->id;
                        $shopId = $params['object']->id_shop;
                        $from = $params['object']->from;
                        $to = $params['object']->to;

                        $current_date = new DateTime(null, new DateTimeZone(@date_default_timezone_get()));
                        $from_date = new DateTime($from, new DateTimeZone(@date_default_timezone_get()));
                        $to_date = new DateTime($to, new DateTimeZone(@date_default_timezone_get()));

                        $needToRun = 0;
                        $unlimited = 0;

                        if ($from_date > $current_date) {
                            $needToRun = 2;
                        } elseif ($to_date > $current_date) {
                            $needToRun = 1;
                        } elseif ($to == '0000-00-00 00:00:00') {
                            $unlimited = 1;
                        }

                        if ($needToRun > 0 && $unlimited == 0) {
                            Db::getInstance()->execute(
                                "INSERT IGNORE INTO " . _DB_PREFIX_ . "mailchimppro_specific_price (`id_specific_price`, `id_product`, `start_date`, `end_date`, `needToRun`, `id_shop`) VALUES ('{$specific_price_id}', '{$product_id}', '{$from}', '{$to}', '{$needToRun}', '{$shopId}')"
                            );
                        }

                        if ($needToRun == 1 || ($needToRun == 0 && $unlimited == 1)) {
                            if (!Configuration::get(MailchimpProConfig::CRONJOB_BASED_SYNC)) {
                                // sync live add sp price
                                $command = new \PrestaChamps\MailchimpPro\Commands\ProductSyncCommand(
                                    $this->context,
                                    $this->getApiClient(),
                                    [$product_id]
                                );
                                $command->setSyncMode($command::SYNC_MODE_REGULAR);
                                $command->setMethod(
                                    $command->getProductExists($product_id)
                                        ? $command::SYNC_METHOD_PATCH
                                        : $command::SYNC_METHOD_POST
                                );
                                $command->execute();
                            } else {
                                // add job add sp price
                                $job = new \PrestaChamps\Queue\Jobs\ProductSyncJob();
                                $job->productId = $product_id;
                                $job->setSyncMode(\PrestaChamps\MailchimpPro\Commands\ProductSyncCommand::SYNC_MODE_REGULAR);
                                $queue = new \PrestaChamps\Queue\Queue();
                                $queue->push($job, 'hook-specific-price-add-after', $shopId);
                            }
                        }


                    }
                } catch (Exception $exception) {
                    $this->context->controller->errors[] = $exception->getMessage();
                    PrestaShopLogger::addLog(
                        "MAILCHIMP_ERROR: {$exception->getMessage()}",
                        1,
                        $exception->getCode(),
                        PrestaChamps\MailchimpPro\Commands\ProductSyncCommand::class,
                        null,
                        true
                    );
                }
            }
        }
    }

    public function hookActionObjectSpecificPriceUpdateAfter($params){
        if ($this->isApiKeySet() && $this->isStoreSynced()) {
            if (Configuration::get(MailchimpProConfig::SYNC_PRODUCTS)) {
                try {
                    if (is_subclass_of($params['object'], 'SpecificPriceCore')) {

                        $product_id = $params['object']->id_product;
                        $specific_price_id = $params['object']->id;
                        $shopId = $params['object']->id_shop;
                        $from = $params['object']->from;
                        $to = $params['object']->to;

                        $current_date = new DateTime(null, new DateTimeZone(@date_default_timezone_get()));
                        $from_date = new DateTime($from, new DateTimeZone(@date_default_timezone_get()));
                        $to_date = new DateTime($to, new DateTimeZone(@date_default_timezone_get()));

                        $needToRun = 0;
                        $unlimited = 0;
                        $needToRunDB = 0;

                        if ($from_date > $current_date) {
                            $needToRun = 2;
                        } elseif ($to_date > $current_date) {
                            $needToRun = 1;
                        } elseif ($to == '0000-00-00 00:00:00') {
                            $unlimited = 1;
                        }

                        $sql = "SELECT * FROM " . _DB_PREFIX_ . "mailchimppro_specific_price WHERE id_specific_price = " . (int)$specific_price_id;

                        $db_specific_price = Db::getInstance()->getRow($sql);

                        if ($db_specific_price) {
                            $needToRunDB = $db_specific_price["needToRun"];
                            $fromDB = $db_specific_price["from"];
                            $toDB = $db_specific_price["to"];

                            $delSql = "DELETE FROM " . _DB_PREFIX_ . "mailchimppro_specific_price WHERE id_specific_price = " . (int)$specific_price_id;
                            $delete = Db::getInstance()->execute($delSql);
                        }

                        if ($needToRun > 0 && $unlimited == 0) {
                            Db::getInstance()->execute(
                                "INSERT IGNORE INTO " . _DB_PREFIX_ . "mailchimppro_specific_price (`id_specific_price`, `id_product`, `start_date`, `end_date`, `needToRun`, `id_shop`) VALUES ('{$specific_price_id}', '{$product_id}', '{$from}', '{$to}', '{$needToRun}', '{$shopId}')"
                            );
                        }

                        if ($needToRun == 1 || ($needToRun == 0 && $unlimited == 1) || $db_specific_price) {
                            if (!Configuration::get(MailchimpProConfig::CRONJOB_BASED_SYNC)) {
                                // sync live edit sp price
                                $command = new \PrestaChamps\MailchimpPro\Commands\ProductSyncCommand(
                                    $this->context,
                                    $this->getApiClient(),
                                    [$product_id]
                                );
                                $command->setSyncMode($command::SYNC_MODE_REGULAR);
                                $command->setMethod(
                                    $command->getProductExists($product_id)
                                        ? $command::SYNC_METHOD_PATCH
                                        : $command::SYNC_METHOD_POST
                                );
                                $command->execute();
                            } else {
                                // add job edit sp price
                                $job = new \PrestaChamps\Queue\Jobs\ProductSyncJob();
                                $job->productId = $product_id;
                                $job->setSyncMode(\PrestaChamps\MailchimpPro\Commands\ProductSyncCommand::SYNC_MODE_REGULAR);
                                $queue = new \PrestaChamps\Queue\Queue();
                                $queue->push($job, 'hook-specific-price-update-after', $shopId);
                            }
                        }
                    }
                } catch (Exception $exception) {
                    $this->context->controller->errors[] = $exception->getMessage();
                    PrestaShopLogger::addLog(
                        "MAILCHIMP_ERROR: {$exception->getMessage()}",
                        1,
                        $exception->getCode(),
                        PrestaChamps\MailchimpPro\Commands\ProductSyncCommand::class,
                        null,
                        true
                    );
                }
            }
        }
    }

    public function hookActionObjectSpecificPriceDeleteAfter($params){
        if ($this->isApiKeySet() && $this->isStoreSynced()) {
            if (Configuration::get(MailchimpProConfig::SYNC_PRODUCTS)) {
                try {
                    if (is_subclass_of($params['object'], 'SpecificPriceCore') && $this->isApiKeySet()) {

                        $product_id = $params['object']->id_product;
                        $specific_price_id = $params['object']->id;
                        $shopId = $params['object']->id_shop;

                        $sql = "SELECT * FROM " . _DB_PREFIX_ . "mailchimppro_specific_price WHERE id_specific_price = " . (int)$specific_price_id;

                        $db_specific_price = Db::getInstance()->getRow($sql);

                        $needToRunDB = 0;

                        if ($db_specific_price) {
                            $needToRunDB = $db_specific_price["needToRun"];

                            $delSql = "DELETE FROM " . _DB_PREFIX_ . "mailchimppro_specific_price WHERE id_specific_price = " . (int)$specific_price_id;
                            $delete = Db::getInstance()->execute($delSql);
                        }

                        if ($needToRunDB == 1 || !$db_specific_price) {
                            if (!Configuration::get(MailchimpProConfig::CRONJOB_BASED_SYNC)) {
                                // sync live delete sp price
                                $command = new \PrestaChamps\MailchimpPro\Commands\ProductSyncCommand(
                                    $this->context,
                                    $this->getApiClient(),
                                    [$product_id]
                                );
                                $command->setSyncMode($command::SYNC_MODE_REGULAR);
                                $command->setMethod(
                                    $command->getProductExists($product_id)
                                        ? $command::SYNC_METHOD_PATCH
                                        : $command::SYNC_METHOD_POST
                                );
                                $command->execute();
                            } else {
                                // add job edit sp price
                                $job = new \PrestaChamps\Queue\Jobs\ProductSyncJob();
                                $job->productId = $product_id;
                                $job->setSyncMode(\PrestaChamps\MailchimpPro\Commands\ProductSyncCommand::SYNC_MODE_REGULAR);
                                $queue = new \PrestaChamps\Queue\Queue();
                                $queue->push($job, 'hook-specific-price-delete-after', $shopId);
                            }
                        }
                    }
                } catch (Exception $exception) {
                    $this->context->controller->errors[] = $exception->getMessage();
                    PrestaShopLogger::addLog(
                        "MAILCHIMP_ERROR: {$exception->getMessage()}",
                        1,
                        $exception->getCode(),
                        PrestaChamps\MailchimpPro\Commands\ProductSyncCommand::class,
                        null,
                        true
                    );
                }
            }
        }
    }

    public static function shopIdTransformer(\Shop $shop)
    {
        if (Configuration::get(MailchimpProConfig::MULTI_INSTANCE_MODE) == 1) {
            return $shop->domain . "_" . $shop->id;
        }

        return $shop->id;
    }

    protected function getShopId()
    {
        return static::shopIdTransformer($this->context->shop);
    }

    public static function getCustomerLanguageIsoCode($isoCode){
        $iso_return = '';
        
        switch ((string)$isoCode){
            case 'fr': 
                $iso_return = 'fr';
                break;
            case 'qc': 
                $iso_return = 'fr_CA';
                break;
            case 'pt':
                $iso_return = 'pt_PT';
                break;
            case 'br':
                $iso_return = 'pt';
                break;
            case 'es': 
                $iso_return = 'es_ES';
                break;
            case 'mx':
                $iso_return = 'es';
                break;
            default:
                $iso_return = $isoCode; 
        }

        return $iso_return;
    }
}
