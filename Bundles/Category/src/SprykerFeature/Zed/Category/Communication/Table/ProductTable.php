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
            SpyAbstractProductTableMap::COL_SKU => 'SKU',
            SpyLocalizedAbstractProductAttributesTableMap::COL_NAME => 'Name',
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
        
        $queryResults = $this->runQuery($query, $config);
        
        $results = [];
        foreach ($queryResults as $productCategory) {
            $results[] = [
                SpyAbstractProductTableMap::COL_SKU => '<input type="checkbox" /> '.$productCategory['SKU'],
                SpyLocalizedAbstractProductAttributesTableMap::COL_NAME => $productCategory['name'],
                //SpyProductCategoryTableMap::COL_FK_PRECONFIG_PRODUCT => $productCategory[SpyProductCategoryTableMap::COL_FK_PRECONFIG_PRODUCT],
                //'checkboxes' => '<input type="checkbox" />',
            ];
        }
        unset($queryResults);
        return $results;
    }
}
