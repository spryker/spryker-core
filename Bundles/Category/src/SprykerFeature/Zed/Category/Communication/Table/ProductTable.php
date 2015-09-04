<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Zed\Category\CategoryConfig;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedAbstractProductAttributesTableMap;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainer;


class ProductTable extends AbstractTable
{
    const TABLE_IDENTIFIER = 'product-table';

    /**
     * @var ProductQueryContainerInterface
     */
    protected $productQueryContainer;


    /**
     * @var LocaleTransfer
     */
    protected $locale;

    /**
     * @var int
     */
    protected $idCategory;

    /**
     * @var string
     */
    //protected $term;

    /**
     * @param ProductQueryContainerInterface $productQueryContainer
     * @param LocaleTransfer $locale
     * @param int $idCategory
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer, LocaleTransfer $locale, $idCategory)
    {
        $this->productQueryContainer = $productQueryContainer;
        $this->locale = $locale;
        //$this->term = $term;
        $this->idCategory = (int) $idCategory;
        $this->defaultUrl = sprintf('productTable?%s=%d', CategoryConfig::PARAM_ID_CATEGORY, $this->idCategory);
        $this->setTableIdentifier(self::TABLE_IDENTIFIER);
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            //'checkboxes' => 'Select All',
            SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT => 'id_abstract_product',
            SpyAbstractProductTableMap::COL_SKU => 'SKU',
            SpyLocalizedAbstractProductAttributesTableMap::COL_NAME => 'Name',
            'checkbox' => 'Selected',
            //SpyProductCategoryTableMap::COL_FK_PRECONFIG_PRODUCT => 'Preconfig',

        ]);
        $config->setSortable([
            SpyAbstractProductTableMap::COL_SKU,
            //SpyLocalizedAbstractProductAttributesTableMap::COL_NAME,
        ]);
        
        $config->setPageLength(10);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->productQueryContainer->queryAbstractProductsBySearchTermFuckingLol(null, $this->locale, $this->idCategory);
        $query->setModelAlias('spy_abstract_product');
        //$query->addAlias('SKU', 'spy_abstract_product.sku');
        
        $queryResults = $this->runQuery($query, $config);
        
        $results = [];
        foreach ($queryResults as $product) {
            $results[] = [
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT => $product['ID_ABSTRACT_PRODUCT'],
                //SpyAbstractProductTableMap::COL_SKU => '<input type="checkbox" onclick="assignNewSelectedProductsClickMarkAsSelected(this, '.$product['ID_ABSTRACT_PRODUCT'].', \''.$product['SKU'].'\', \''.urlencode($product['name']).'\'); return" /> '.$product['SKU'],
                SpyAbstractProductTableMap::COL_SKU => $product['SKU'],
                SpyLocalizedAbstractProductAttributesTableMap::COL_NAME => $product['name'],
                'checkbox' => '<input id="all_products_checkbox_'.$product['ID_ABSTRACT_PRODUCT'].'" type="checkbox" onclick="allProductsClickMarkAsSelected(this.checked, '.$product['ID_ABSTRACT_PRODUCT'].', \''.$product['SKU'].'\', \''.urlencode($product['name']).'\'); return" /> ',
                //SpyProductCategoryTableMap::COL_FK_PRECONFIG_PRODUCT => $productCategory[SpyProductCategoryTableMap::COL_FK_PRECONFIG_PRODUCT],
                //'checkboxes' => '<input type="checkbox" />',
            ];
        }
        unset($queryResults);
        return $results;
    }
}
