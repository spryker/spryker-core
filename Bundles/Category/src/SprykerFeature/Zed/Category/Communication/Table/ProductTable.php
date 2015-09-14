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
     * @param ProductQueryContainerInterface $productQueryContainer
     * @param LocaleTransfer $locale
     * @param int $idCategory
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer, LocaleTransfer $locale, $idCategory)
    {
        $this->productQueryContainer = $productQueryContainer;
        $this->locale = $locale;
        $this->idCategory = (int)$idCategory;
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
            SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT => 'ID',
            SpyAbstractProductTableMap::COL_SKU => 'SKU',
            SpyLocalizedAbstractProductAttributesTableMap::COL_NAME => 'Name',
            'checkbox' => 'Selected',

        ]);
        $config->setSortable([
            SpyAbstractProductTableMap::COL_SKU,
            SpyLocalizedAbstractProductAttributesTableMap::COL_NAME,
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
        $query = $this->productQueryContainer->queryAbstractProductsBySearchTerm(null, $this->locale, $this->idCategory);
        $query->setModelAlias('spy_abstract_product');

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $product) {
            //die(dump($product));
            $results[] = [
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT => $product[SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT],
                SpyAbstractProductTableMap::COL_SKU => $product[SpyAbstractProductTableMap::COL_SKU],
                SpyLocalizedAbstractProductAttributesTableMap::COL_NAME => $product['name'],
                'checkbox' => '<input id="all_products_checkbox_' .
                    $product[SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT] . 
                    '" type="checkbox" onclick="allProductsClickMarkAsSelected(this.checked, ' . 
                    $product[SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT] . 
                    ', \'' . $product[SpyAbstractProductTableMap::COL_SKU] . '\', \'' . 
                    urlencode($product['name']) . '\'); return" /> ',
            ];
        }
        unset($queryResults);
        return $results;
    }
}
