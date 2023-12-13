<?php

namespace Sendinblue\Services;

use PrestaShop\PrestaShop\Adapter\Validate;
use PrestaShop\PrestaShop\Core\Domain\Order\Repository\OrderRepository;
use PrestaShop\PrestaShop\Core\Domain\Order\Query\GetTotalOrdersCountQuery;
use PrestaShop\PrestaShop\Core\Domain\Order\QueryHandler\GetTotalOrdersCountQueryHandler;

if (!defined('_PS_VERSION_')) {
    exit;
}

class OrderService
{
    private $idShop;

    public function __construct()
    {
        $this->idShop = \ContextCore::getContext()->shop->id;
    }

    public function getTotalOrderCount()
    {
        $result = \DbCore::getInstance()->executeS(
            sprintf(
                "SELECT count(*) AS `count`
            FROM  `%sorders` o
            WHERE o.`id_shop` = %d",
                _DB_PREFIX_,
                $this->idShop
            )
        );

        return isset($result[0]['count']) ? (int) $result[0]['count'] : 0;
    }

    public function getOrders($limit, $offset)
    {
        $sql = sprintf(
            "SELECT o.`id_order`, o.`reference`, o.`total_paid_tax_incl` AS total_paid, o.`date_add`, o.`date_upd`, o.`current_state`, SUM(od.`total_refunded_tax_incl`) as refunded,
                c.`firstname`, c.`lastname`, c.`email`, 
                CONCAT(a.`address1`, ' ', a.`address2`) AS address,
                a.`city`, a.`postcode`, a.`phone`,
                co.`iso_code` AS countryCode,
                s.`name` AS region,
                b.`name` AS order_status,
                o.`payment` AS paymentMethod,
                GROUP_CONCAT(DISTINCT cr_lang.`name` SEPARATOR ', ') AS distinct_coupons,
                GROUP_CONCAT(
                    JSON_OBJECT(
                        'id_product', od.`product_id`,
                        'id_variant', od.`product_attribute_id`,
                        'name', p_lang.`name`,
                        'price', od.`product_price`,
                        'quantity', od.`product_quantity`
                    )
                ) AS products_json
            FROM `%sorders` o
            LEFT JOIN `%scustomer` c ON (o.`id_customer` = c.`id_customer`)
            LEFT JOIN `%saddress` a ON (o.`id_address_invoice` = a.`id_address`)
            LEFT JOIN `%sorder_state_lang` b ON (b.`id_order_state` = o.`current_state` AND b.`id_lang` = 1)
            LEFT JOIN `%scountry` co ON (co.`id_country` = a.`id_country`)
            LEFT JOIN `%sstate` s ON (s.`id_state` = a.`id_state`)
            LEFT JOIN `%sorder_cart_rule` ocr ON (ocr.`id_order` = o.`id_order`)
            LEFT JOIN `%scart_rule_lang` cr_lang ON (ocr.`id_cart_rule` = cr_lang.`id_cart_rule` AND cr_lang.`id_lang` = 1)
            LEFT JOIN `%sorder_detail` od ON (od.`id_order` = o.`id_order`)
            LEFT JOIN `%sproduct` p ON (p.`id_product` = od.`product_id`)
            LEFT JOIN `%sproduct_lang` p_lang ON (p.`id_product` = p_lang.`id_product` AND p_lang.`id_lang` = 1 AND p_lang.`id_shop` = %d)
            WHERE o.`id_shop` = %d
            AND o.`current_state` != 0
            GROUP BY o.`id_order`
            LIMIT %d, %d",
            _DB_PREFIX_,
            _DB_PREFIX_,
            _DB_PREFIX_,
            _DB_PREFIX_,
            _DB_PREFIX_,
            _DB_PREFIX_,
            _DB_PREFIX_,
            _DB_PREFIX_,
            _DB_PREFIX_,
            _DB_PREFIX_,
            _DB_PREFIX_,
            $this->idShop,
            $this->idShop,
            $offset,
            $limit
        );

        $orders = \DbCore::getInstance()->executeS($sql);

        foreach ($orders as &$order) {
            $billing = [
                'address' => $order['address'],
                'city' => $order['city'],
                'postCode' => $order['postcode'],
                'phone' => $order['phone'],
                'countryCode' => $order['countryCode'],
                'paymentMethod' => $order['paymentMethod'],
                'region' => $order['region'],
            ];

            $order['billing'] = $billing;
            unset($order['address'], $order['city'], $order['postcode'], $order['phone'], $order['countryCode'], $order['paymentMethod'], $order['region']);

            // Convert products JSON string to an array of products
            $order['products'] = [];
            if (!empty($order['products_json'])) {
                $order['products'] = json_decode('[' . $order['products_json'] . ']', true);
            }
            unset($order['products_json']);

            // Convert coupons string to an array of distinct coupons
            $order['coupons'] = [];
            if (!empty($order['distinct_coupons'])) {
                $order['coupons'] = explode(', ', $order['distinct_coupons']);
            }
            unset($order['distinct_coupons']);

            // Payment Error or Cancelled or Refunded
            if ($order['current_state'] == 8 || $order['current_state'] == 7 || $order['current_state'] == 6) {
                $order['total_paid'] = strval(0);
            } else {
                $order['total_paid'] = strval($order['total_paid'] - $order['refunded']);
            }
            unset($order['refunded']);
        }

        return ['orders' => $orders];
    }
}
