<?php

namespace SprykerFeature\Zed\Product\Communication\Table;

use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProductQuery;

class ProductTable extends AbstractTable
{

    const OPTIONS = 'Options';

    /**
     * @var SpyAbstractProductQuery
     */
    protected $productQuery;

    /**
     * @param SpyAbstractProductQuery $productQuery
     */
    public function __construct(SpyAbstractProductQuery $productQuery)
    {
        $this->productQuery = $productQuery;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT => 'Id Abstract Product',
            SpyAbstractProductTableMap::COL_SKU => 'SKU',
            self::OPTIONS => self::OPTIONS,
        ]);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $queryResults = $this->runQuery($this->productQuery, $config);

        $abstractProducts = [];
        foreach ($queryResults as $item) {
            $abstractProducts[] = [
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT => $item[SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT],
                SpyAbstractProductTableMap::COL_SKU => $item[SpyAbstractProductTableMap::COL_SKU],
                self::OPTIONS => $this->getViewUrl($item),
            ];
        }

        return $abstractProducts;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function getViewUrl(array $item)
    {
        return sprintf(
            '<a href="/product/index/view/?id-abstract-product=%d" class="btn btn-sm btn-primary">%s</a>',
            $item[SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT],
            'View'
        );
    }

}
