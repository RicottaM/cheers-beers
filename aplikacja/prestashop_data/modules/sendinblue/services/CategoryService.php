<?php

namespace Sendinblue\Services;

if (!defined('_PS_VERSION_')) {
    exit;
}

class CategoryService
{
    const DEFAULT_LIMIT = 100;
    const DEFAULT_PAGE = 1;

    /**
     * @var int|null
     */
    private $idShop;

    /**
     * @var false|string
     */
    private $defaultLanguage;

    /**
     * @var false|int|string
     */
    private $idLang;

    public function __construct()
    {
        $this->idShop = \ContextCore::getContext()->shop->id;
        $this->defaultLanguage = \ConfigurationCore::get('PS_LANG_DEFAULT');
        $this->idLang = ! empty($this->defaultLanguage) ? $this->defaultLanguage : \ContextCore::getContext()->language->id;
    }

    public function getTotalCategoryCount()
    {
        $result = \DbCore::getInstance()->executeS(
            sprintf(
            "SELECT count(DISTINCT(c.`id_category`)) AS `count`
        FROM  `%scategory` c
        LEFT JOIN `%scategory_lang` cl ON (cl.`id_category` = c.`id_category`)
        WHERE c.`id_shop_default` = %d AND cl.`id_lang` = %d
        ",
            _DB_PREFIX_,
            _DB_PREFIX_,
            $this->idShop,
            $this->idLang
            )
        );

        return isset($result[0]['count']) ? (int) $result[0]['count'] : 0;
    }

    /**
     * @param int $page
     * @param int $itemsPerPage
     * @return array
     */
    public function getCategories($offset, $itemsPerPage)
    {        
        $sql = sprintf(
            "SELECT  DISTINCT(c.`id_category`), c.`id_parent`, c.`id_shop_default`,  
            cl.`name`, cl.`description` , cl.`id_lang` 
        FROM  `%scategory` c
        LEFT JOIN `%scategory_lang` cl ON (cl.`id_category` = c.`id_category`)
        WHERE c.`id_shop_default` = %d AND cl.`id_lang` = %d
        LIMIT %d, %d",
            _DB_PREFIX_,
            _DB_PREFIX_,
            $this->idShop,
            $this->idLang,
            $offset,
            $itemsPerPage,
        );

        $result = \DbCore::getInstance()->executeS($sql);

        $categories = [];
        foreach ($result as $key => $category) {
            $categories[$key]['id']             = (int) $category['id_category'];
            $categories[$key]['name']           = $category['name'];
            $categories[$key]['description']    = $category['description'];
            $categories[$key]['url'] = \Context::getContext()->link->getCategoryLink($category['id_category']);
        }

       return $categories;
    }

    /**
     * @param int $page
     * @return int
     */
    public function fixPage($page)
    {
        return empty($page) ? self::DEFAULT_PAGE : $page;
    }
}
