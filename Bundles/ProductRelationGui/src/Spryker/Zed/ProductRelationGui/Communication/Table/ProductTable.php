<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductRelationGui\Communication\Controller\ViewController;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductRelationGui\Dependency\Service\ProductRelationGuiToUtilEncodingServiceInterface;

class ProductTable extends AbstractProductTable
{
    protected const COL_ACTIONS = 'Actions';
    protected const COL_STATUS = 'Status';

    /**
     * @var \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductRelationGui\Dependency\Service\ProductRelationGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var int|null
     */
    protected $idProductRelation;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected $productAbstractQuery;

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productAbstractQuery
     * @param \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductRelationGui\Dependency\Service\ProductRelationGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param int|null $idProductRelation
     */
    public function __construct(
        SpyProductAbstractQuery $productAbstractQuery,
        ProductRelationGuiToLocaleFacadeInterface $localeFacade,
        ProductRelationGuiToUtilEncodingServiceInterface $utilEncodingService,
        ?int $idProductRelation = null
    ) {
        $this->productAbstractQuery = $productAbstractQuery;
        $this->localeFacade = $localeFacade;
        $this->utilEncodingService = $utilEncodingService;

        $this->setTableIdentifier('product-table');
        $this->idProductRelation = $idProductRelation;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $this->setTableUrl($config);
        $this->setHeaders($config);
        $this->setRawColumns($config);
        $this->setSortableFields($config);
        $this->setSearchableFields($config);
        $this->setDefaultSortField($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setHeaders(TableConfiguration $config): void
    {
        $header = [
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => '#',
            SpyProductAbstractTableMap::COL_SKU => 'Sku',
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => 'Name',
        ];

        if ($this->idProductRelation === null) {
            $header[static::COL_ACTIONS] = static::COL_ACTIONS;
        }

        $config->setHeader($header);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setRawColumns(TableConfiguration $config): void
    {
        $config->setRawColumns([
            static::COL_ACTIONS,
            static::COL_STATUS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSortableFields(TableConfiguration $config): void
    {
        $config->setSortable([
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSearchableFields(TableConfiguration $config): void
    {
        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setDefaultSortField(TableConfiguration $config): void
    {
        $config->setDefaultSortField(
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            TableConfiguration::SORT_DESC
        );
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setTableUrl(TableConfiguration $config): void
    {
        $url = Url::generate('table', [
                ViewController::URL_PARAM_ID_PRODUCT_RELATION => $this->idProductRelation,
            ])->build();

        $config->setUrl($url);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        /** @var \Propel\Runtime\ActiveQuery\ModelCriteria $query */
        $query = $this->prepareQuery();

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $item) {
            $results[] = $this->mapResults($item);
        }

        return $results;
    }

    /**
     * @return \Propel\Runtime\ActiveQuery\Criteria
     */
    protected function prepareQuery(): Criteria
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();

        if ($this->idProductRelation !== null) {
            return $this->queryProductsByIdLocaleAndIdRelation(
                $localeTransfer->getIdLocale(),
                $this->idProductRelation
            );
        }

        return $this->queryProductsByFkLocale($localeTransfer->getIdLocale());
    }

    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery|\Propel\Runtime\ActiveQuery\Criteria
     */
    protected function queryProductsByFkLocale(int $idLocale): Criteria
    {
        return $this->productAbstractQuery
            ->select([
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                SpyProductAbstractTableMap::COL_SKU,
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
            ])
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->addGroupByColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT)
            ->addGroupByColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME);
    }

    /**
     * @param int $idLocale
     * @param int $idProductRelation
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function queryProductsByIdLocaleAndIdRelation(int $idLocale, int $idProductRelation): SpyProductAbstractQuery
    {
        return $this->queryProductsByFkLocale($idLocale)
            ->useSpyProductRelationProductAbstractQuery()
                ->filterByFkProductRelation($idProductRelation)
            ->endUse();
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function mapResults(array $item): array
    {
        $results = [
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => $item[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT],
            SpyProductAbstractTableMap::COL_SKU => $item[SpyProductAbstractTableMap::COL_SKU],
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => $item[SpyProductAbstractLocalizedAttributesTableMap::COL_NAME],
        ];

        if ($this->idProductRelation === null) {
            $results[static::COL_ACTIONS] = $this->buildSelectButton($item);
        }

        return $results;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function buildSelectButton(array $item): string
    {
        return $this->generateViewButton(
            '#',
            'Select',
            [
                'data-select-product' => $item[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT],
                'id' => 'select-product-' . $item[SpyProductAbstractTableMap::COL_SKU],
                'icon' => '',
            ]
        );
    }
}
