<?php
/**
 * 2007-2022 Sendinblue
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@sendinblue.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    Sendinblue <contact@sendinblue.com>
 * @copyright 2007-2022 Sendinblue
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of Sendinblue
 */

namespace Sendinblue\Hooks;

use Sendinblue\Services\ApiClientService;
use Sendinblue\Services\ConfigService;

if (!defined('_PS_VERSION_')) {
    exit;
}

class ActionOrderEditHook extends AbstractHook
{
    public function __construct()
    {
        $this->idShop = \ContextCore::getContext()->shop->id;
    }

    /**
     * @param \OrderCore|\Shipping $order
     */
    public function handleEvent($order)
    {
        
    }

    public function handleOrderEvents($params)
    {
        $orderPayload = $this->prepareCommonPayloadForOrderEvents($params);

        $idOrder = $params['order']->id;
        $orderDetails = \OrderDetail::getList((int)$idOrder);

        $orderPayload['products'] = [];
        $productDetails = \OrderDetail::getList((int)$idOrder);

        foreach ($productDetails as $k => $v) {

            $quantity = $v['product_quantity'];
            if (isset($v['product_quantity_refunded'])) {
                $quantity = $v['product_quantity'] - $v['product_quantity_refunded'];
            }

            $items = [
                'name' => $v['product_name'],
                'id_product' => intval($v['product_id']),
                'quantity' => intval($quantity),
                'price' => floatval($v['unit_price_tax_incl']),
            ];
            array_push($orderPayload['products'], $items);
        }

        $orderEventURI = ApiClientService::EVENT_ORDER_CREATED_URI;
        $this->getApiClientService()->sendOrderData($orderPayload, $orderEventURI);
    }

    public function handleOrderRefundEvents($params)
    {
        $orderPayload = $this->prepareCommonPayloadForOrderEvents($params);

        $idOrder = $params['order']->id;
        $order = new \Order($idOrder);
        $orderDetails = \OrderDetail::getList((int)$idOrder);

        $refundedProducts = $params['productList'];
        
        $totalAmountRefunded = 0.0;
        foreach ($refundedProducts as $krp => $rp) {
            foreach ($orderDetails as $kod => $od) {
                if ($rp['id_order_detail'] == $od['id_order_detail']) {
                    $orderDetails[$kod]['product_quantity_refunded'] =  $od['product_quantity_refunded'] + $rp['quantity'];

                    $orderDetails[$kod]['total_refunded_tax_incl'] =  $od['total_refunded_tax_incl'] + $rp['total_refunded_tax_incl'];
                    
                    $totalAmountRefunded = $totalAmountRefunded + $orderDetails[$kod]['total_refunded_tax_incl'];
                } else {
                    $totalAmountRefunded = $totalAmountRefunded + $od['total_refunded_tax_incl'];
                }
            }
        }

        $orderPayload['final_amount'] = strval($order->total_paid - $totalAmountRefunded); 
        $orderPayload['total_paid'] = strval($order->total_paid - $totalAmountRefunded); 
        $orderPayload['total'] = strval($order->total_paid - $totalAmountRefunded); 
        $orderPayload['total_tax'] = strval($order->total_paid - $totalAmountRefunded - $orderPayload['shipping_total'] + $orderPayload['shipping_tax']); 
        $orderPayload['products'] = [];

        if (!empty($orderDetails)) {
            foreach ($orderDetails as $k => $v) {
                $items = [
                    'name' => $v['product_name'],
                    'id_product' => intval($v['product_id']),
                    'quantity' => intval($v['product_quantity']),
                    'price' => floatval($v['unit_price_tax_incl']),
                ];
                array_push($orderPayload['products'], $items);
            }
        }

        $orderEventURI = ApiClientService::EVENT_ORDER_REFUND_URI;
        $this->getApiClientService()->sendOrderData($orderPayload, $orderEventURI);
    }

    protected function prepareCommonPayloadForOrderEvents($params)
    {
        $idOrder = $params['order']->id;
        $order = new \Order($idOrder);
        $customer = new \Customer($order->id_customer);
        $address = new \Address($order->id_address_delivery);
        $state = new \State ($address->id_state);
        $country = new \Country ($address->id_country);
        
        $currentStatus = \Db::getInstance()->getValue(
            'SELECT `name`
                FROM `' . _DB_PREFIX_ . 'order_state_lang`
                WHERE `id_order_state` = "' . pSQL($order->current_state) . '"'
            );

        $orderPayload = [
            'id_order' => $idOrder ,
            'order_status' => $currentStatus,
            'discount_total' => $order->total_discounts,
            'discount_tax' => $order->total_discounts_tax_incl - $order->total_discounts_tax_excl,
            'shipping_total' => $order->total_shipping,
            'shipping_tax' => $order->total_shipping_tax_incl - $order->total_shipping_tax_excl,
            'total_paid' => strval($order->total_paid_tax_incl),
            'total_tax' => $order->total_paid_tax_incl - $order->total_paid_tax_excl,
            'final_amount' => strval($order->total_paid_tax_incl),
            'customer_note' => $order->note,
            'date_add' => $order->date_add,
            'date_upd' => $order->date_upd,
            'payment_method' => $order->payment,
            'email' => $customer->email,
            'phone' => $address->phone,
        ];

        $orderPayload['shipping']['country_code'] = $country->iso_code;
        $orderPayload['shipping']['country'] = $address->country;
        $orderPayload['billing']['countryCode'] = $country->iso_code;
        $orderPayload['billing']['country'] =  $address->country;
        $orderPayload['billing']['city'] = $address->city;
        $orderPayload['billing']['postCode'] = $address->postcode;
        $orderPayload['billing']['region'] = $state->name;
        $orderPayload['billing']['address'] = $address->address1 . " " . $address->address2;
        $orderPayload['billing']['phone'] = $address->phone;
        $orderPayload['billing']['paymentMethod'] = $order->payment;

        if (!empty($order->getCartRules())) {
            $orderPayload['coupons'] = [];
            foreach ($order->getCartRules() as $k => $v) {
                array_push($orderPayload['coupons'], $v['name']);
            }
        }

        return $orderPayload;
    }
}
