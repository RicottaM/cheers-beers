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

class ActionProductsHook extends AbstractHook
{
    const EVENT_PRODUCT_CREATED = '/events/%s/products_sync?action=product_created';

    const EVENT_PRODUCT_DELETED = '/events/%s/products_sync?action=product_deleted';

    const EVENT_PRODUCT_UPDATED = '/events/%s/products_sync?action=product_updated';

    public function handleEvent($data)
    {
        $product = [];
        if (empty($data['id_product'])) {
            return $product;
        }

        $defaultLanguage = (int) \Context::getContext()->language->id;

        $data = new \Product($data['id_product'], false, $defaultLanguage);
        $link = new \Link();

        $product['id'] = (int) $data->id;
        $product['name'] = $data->name;
        $product['date_created'] = date(DATE_ATOM,strtotime($data->date_add));
        $product['date_modified'] = date(DATE_ATOM,strtotime($data->date_upd));
        $product['permalink'] = $link->getProductLink($data);
        $product['type'] = $data->product_type;
        $product['status'] = $data->active ? 'active' : 'inactive';
        $product['description'] = $data->description;
        $product['short_description'] = $data->description_short;
        $product['sku'] = $data->reference;
        $product['price'] = (string) \ProductCore::getPriceStatic($data->id);
        $product['regular_price'] = $data->wholesale_price;
        $product['sale_price'] = $data->price;
        $product['stock_quantity'] = (int) $data->quantity;
        $product['stock_status'] = $data->state ? 'available' : 'unavailable';

        
        if (!empty($data->id_category_default)) {
            $category = new \Category($data->id_category_default, $defaultLanguage);
            $product['categories'][] = (object) [
                'id' => (int) $category->id,
                'name' => $category->name,
                'slug' => $category->link_rewrite,
                'parent' => (int) $category->id_parent,
                'description' => $category->description,
                'display' => $category->active ? 'active' : 'inactive',
                'image' => (object) [
                    'id' => (int) $category->id_image,
                    'src' => "http://" . $link->getImageLink($category->link_rewrite, $category->id_image)
                ],
                'menu_order' => (int) $category->level_depth,
            ];
        }

        $imagesQuery = sprintf('SELECT id_image FROM %simage_shop WHERE id_product = %s AND id_shop = %s AND cover = %d', _DB_PREFIX_, $data->id, $data->id_shop_default, 1);
        $images = \DbCore::getInstance()->executeS($imagesQuery, true, false);
        if (!empty($images)) {
            $product['images'] = [];
            foreach ($images as $image) {
                $img_obj = (object) [
                    'id' => (int) $image['id_image'],
                    'src' => "http://" . $link->getImageLink($data->link_rewrite, $image['id_image'])
                ];
                array_push($product['images'],$img_obj);
            }
        }

        return $product;
    }

    public function productAddEvent($product)
    {
        try {
            $product = $this->handleEvent($product);
            if (!empty($product)) {
                $this->getApiClientService()->productEvents($product, self::EVENT_PRODUCT_CREATED);  
            }
        } catch (\Exception $e) {
            $this->logError($e->getMessage());
        }
    }

    public function productUpdateEvent($product)
    {
        try {
            $product = $this->handleEvent($product);
            if (!empty($product)) {
                $this->getApiClientService()->productEvents($product, self::EVENT_PRODUCT_UPDATED);
            }
        } catch (\Exception $e) {
            $this->logError($e->getMessage());
        }
    }

    public function productDeleteEvent($product)
    {
        try {
            if (!empty($product['id_product'])) {
                $data = [];
                $data['id'] = (int) $product['id_product'];
                $this->getApiClientService()->productEvents($data, self::EVENT_PRODUCT_DELETED);
            }
        } catch (\Exception $e) {
            $this->logError($e->getMessage());
        }
    }
}
