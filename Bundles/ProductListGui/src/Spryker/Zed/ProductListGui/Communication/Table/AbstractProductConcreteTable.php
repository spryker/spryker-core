<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\ProductListGui\ProductListGuiConstants;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToLocaleFacadeInterface;

abstract class AbstractProductConcreteTable extends AbstractTable
{
    protected const DEFAULT_URL = 'table';
    protected const TABLE_IDENTIFIER = 'table';

    protected const COLUMN_ID = SpyProductTableMap::COL_ID_PRODUCT;
    protected const COLUMN_SKU = SpyProductTableMap::COL_SKU;
    protected const COLUMN_NAME = SpyProductLocalizedAttributesTableMap::COL_NAME;
    protected const COLUMN_ACTION = 'action';

    /**
     * @var \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected $spyProductQuery;

    /**
     * @module Product
     *
     * @param \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToLocaleFacadeInterface $localeFacade
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $spyProductQuery
     */
    public function __construct(
        ProductListGuiToLocaleFacadeInterface $localeFacade,
        SpyProductQuery $spyProductQuery
    ) {
        $this->localeFacade = $localeFacade;
        $this->spyProductQuery = $spyProductQuery;
        $this->defaultUrl = static::DEFAULT_URL;
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COLUMN_ID => 'ID',
            static::COLUMN_SKU => 'SKU',
            static::COLUMN_NAME => 'Name',
            static::COLUMN_ACTION => 'Selected',
        ]);

        $config->setSearchable([
            static::COLUMN_ID,
            static::COLUMN_SKU,
            static::COLUMN_NAME,
        ]);

        $config->setSortable([
            static::COLUMN_ID,
            static::COLUMN_SKU,
            static::COLUMN_NAME,
        ]);

        $config->addRawColumn(self::COLUMN_ACTION);
        $config->setUrl($this->getTableUrl($config));

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return string
     */
    protected function getTableUrl(TableConfiguration $config): string
    {
        $tableUrl = ($config->getUrl() === null) ? $this->defaultUrl : $config->getUrl();

        if ($this->getIdProductList()) {
            $tableUrl = Url::generate($tableUrl, [ProductListGuiConstants::URL_PARAM_ID_PRODUCT_LIST => $this->getIdProductList()]);
        }

        return $tableUrl;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $spyProductQuery = $this->buildQuery();

        $queryResults = $this->runQuery($spyProductQuery, $config);

        $results = [];
        foreach ($queryResults as $productdata) {
            $results[] = $this->buildDataRow($productdata);
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param string[] $product
     *
     * @return string[]
     */
    protected function buildDataRow(array $product): array
    {
        return [
            static::COLUMN_ID => $product[SpyProductTableMap::COL_ID_PRODUCT],
            static::COLUMN_SKU => $product[SpyProductTableMap::COL_SKU],
            static::COLUMN_NAME => $product[SpyProductLocalizedAttributesTableMap::COL_NAME],
            static::COLUMN_ACTION => sprintf(
                '<input class="%s-all-products-checkbox" type="checkbox"  value="%d">',
                static::TABLE_IDENTIFIER,
                $product[SpyProductTableMap::COL_ID_PRODUCT]
            ),
        ];
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function buildQuery(): SpyProductQuery
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();
        $localeTransfer->requireIdLocale();

        $this->spyProductQuery
            ->joinSpyProductLocalizedAttributes()
            ->useSpyProductLocalizedAttributesQuery()
                ->filterByFkLocale($localeTransfer->getIdLocale())
            ->endUse()
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME)
            ->select(
                [
                    SpyProductTableMap::COL_ID_PRODUCT,
                    SpyProductTableMap::COL_SKU,
                    SpyProductLocalizedAttributesTableMap::COL_NAME,
                ]
            );

        return $this->filterQuery($this->spyProductQuery);
    }

    /**
     * @return int
     */
    protected function getIdProductList(): int
    {
        return $this->request->query->getInt(ProductListGuiConstants::URL_PARAM_ID_PRODUCT_LIST, 0);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $spyProductQuery
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    abstract protected function filterQuery(SpyProductQuery $spyProductQuery): SpyProductQuery;
}
