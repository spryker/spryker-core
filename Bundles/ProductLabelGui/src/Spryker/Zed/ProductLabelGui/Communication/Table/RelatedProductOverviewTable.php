<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class RelatedProductOverviewTable extends AbstractRelatedProductTable
{
    /**
     * @var string
     */
    public const PARAM_ID_PRODUCT_LABEL = 'id-product-label';

    /**
     * @var string
     */
    public const TABLE_IDENTIFIER = 'related-products-table';

    /**
     * @var string
     */
    public const COL_PRODUCT_ABSTRACT_SKU = SpyProductAbstractTableMap::COL_SKU;

    /**
     * @var string
     */
    public const COL_PRODUCT_ABSTRACT_RELATION_COUNT = 'abstract_product_relation_count';

    /**
     * @var string
     */
    public const COL_ACTIONS = 'actions';

    /**
     * @uses \Spryker\Zed\ProductManagement\Communication\Controller\ViewController::PARAM_ID_PRODUCT_ABSTRACT
     *
     * @var string
     */
    protected const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);

        $this->configureHeader($config);
        $this->configureRawColumns($config);
        $this->configureSorting($config);
        $this->configureSearching($config);
        $this->configureUrl($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureHeader(TableConfiguration $config)
    {
        $header = [
            static::COL_PRODUCT_ABSTRACT_SKU => 'SKU',
            static::COL_PRODUCT_ABSTRACT_NAME => 'Name',
            static::COL_PRODUCT_ABSTRACT_CATEGORIES => 'Categories',
        ];

        $header = array_merge($header, [
            static::COL_PRODUCT_ABSTRACT_RELATION_COUNT => '# of Other Labels',
            static::COL_PRODUCT_ABSTRACT_STATUS => 'Status',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setHeader($header);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureRawColumns(TableConfiguration $config)
    {
        $config->setRawColumns([
            RelatedProductTableQueryBuilder::RESULT_FIELD_PRODUCT_CONCRETE_STATES_CSV,
            static::COL_ACTIONS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureSorting(TableConfiguration $config)
    {
        $config->setDefaultSortField(
            static::COL_PRODUCT_ABSTRACT_SKU,
            TableConfiguration::SORT_ASC,
        );

        $config->setSortable([
            static::COL_PRODUCT_ABSTRACT_SKU,
            static::COL_PRODUCT_ABSTRACT_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureSearching(TableConfiguration $config)
    {
        $config->setSearchable([
            static::COL_PRODUCT_ABSTRACT_SKU,
            static::COL_PRODUCT_ABSTRACT_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureUrl(TableConfiguration $config)
    {
        $config->setUrl(sprintf(
            '%s?%s=%s',
            $this->defaultUrl,
            static::PARAM_ID_PRODUCT_LABEL,
            (int)$this->idProductLabel,
        ));
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function getQuery(): SpyProductAbstractQuery
    {
        return $this->tableQueryBuilder->buildAssignedProductQuery($this->idProductLabel);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductLabelGui\Communication\Table\RelatedProductOverviewTable::prepareRowData()} instead.
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return array
     */
    protected function getRow(SpyProductAbstract $productAbstractEntity)
    {
        $row = parent::getRow($productAbstractEntity);

        $row[static::COL_PRODUCT_ABSTRACT_SKU] = $productAbstractEntity->getSku();
        $row[static::COL_PRODUCT_ABSTRACT_RELATION_COUNT] = $this->getAdditionalRelationCountColumn($productAbstractEntity);
        $row[static::COL_ACTIONS] = $this->getActionsColumn($productAbstractEntity);

        return $row;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     * @param array<int, array> $categoryNames
     * @param array<int, int> $additionalRelationsCount
     *
     * @return array
     */
    protected function prepareRowData(SpyProductAbstract $productAbstractEntity, array $categoryNames, array $additionalRelationsCount = []): array
    {
        $row = parent::prepareRowData($productAbstractEntity, $categoryNames);

        $row[static::COL_PRODUCT_ABSTRACT_SKU] = $productAbstractEntity->getSku();
        $row[static::COL_PRODUCT_ABSTRACT_RELATION_COUNT] = $this->getAdditionalRelationCountColumnValue($additionalRelationsCount, $productAbstractEntity->getIdProductAbstract());
        $row[static::COL_ACTIONS] = $this->getActionsColumn($productAbstractEntity);

        return $row;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductLabelGui\Communication\Table\RelatedProductOverviewTable::getAdditionalRelationCountColumnValue()} instead.
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return int
     */
    protected function getAdditionalRelationCountColumn(SpyProductAbstract $productAbstractEntity)
    {
        return SpyProductLabelProductAbstractQuery::create()
                ->filterByFkProductAbstract($productAbstractEntity->getIdProductAbstract())
                ->count() - 1;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getActionsColumn(SpyProductAbstract $productAbstractEntity)
    {
        $actionButtons = [
            $this->generateViewProductInZedButton($productAbstractEntity),
        ];

        return implode(' ', $actionButtons);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function generateViewProductInZedButton(SpyProductAbstract $productAbstractEntity)
    {
        return $this->generateViewButton(
            Url::generate(
                '/product-management/view',
                [
                    static::PARAM_ID_PRODUCT_ABSTRACT => $productAbstractEntity->getIdProductAbstract(),
                ],
            ),
            'View',
        );
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int, int>
     */
    protected function getAdditionalRelationsCount(array $productAbstractIds): array
    {
        return $this->productLabelGuiRepository
            ->getAdditionalRelationsCountIndexedByIdProductAbstract($productAbstractIds);
    }

    /**
     * @param array<int, int> $additionalRelationsCount
     * @param int $idProductAbstract
     *
     * @return int
     */
    protected function getAdditionalRelationCountColumnValue(array $additionalRelationsCount, int $idProductAbstract): int
    {
        return $additionalRelationsCount[$idProductAbstract] ?? 0;
    }
}
