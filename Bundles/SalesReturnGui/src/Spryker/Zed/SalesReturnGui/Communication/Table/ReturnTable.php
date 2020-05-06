<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Table;

use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\SalesReturn\Persistence\Map\SpySalesReturnItemTableMap;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturn;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\SalesReturnGui\Dependency\Service\SalesReturnGuiToUtilDateTimeServiceInterface;

class ReturnTable extends AbstractTable
{
    protected const COL_RETURN_ID = 'id_sales_return';
    protected const COL_RETURN_REFERENCE = 'return_reference';
    protected const COL_ORDER_REFERENCE = 'order_reference';
    protected const COL_RETURNED_PRODUCTS = 'returned_products';
    protected const COL_RETURN_DATE = 'created_at';
    protected const COL_STATE = 'state';
    protected const COL_ACTIONS = 'actions';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\ViewController::indexAction()
     */
    protected const ROUTE_VIEW_RETURN = '/sales-return-gui/view';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\SlipController::indexAction()
     */
    protected const ROUTE_PRINT_SLIP = '/sales-return-gui/slip';

    protected const PARAM_ID_SALES_RETURN = 'id-sales-return';

    protected const ITEM_STATE_TO_LABEL_CLASS_MAPPING = [
        'returned' => 'label-inverse',
        'refunded' => 'label-info',
        'closed' => 'label-danger',
    ];

    /**
     * @var \Spryker\Zed\SalesReturnGui\Dependency\Service\SalesReturnGuiToUtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @var \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery
     */
    protected $salesReturnQuery;

    /**
     * @param \Spryker\Zed\SalesReturnGui\Dependency\Service\SalesReturnGuiToUtilDateTimeServiceInterface $utilDateTimeService
     * @param \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery $salesReturnQuery
     */
    public function __construct(
        SalesReturnGuiToUtilDateTimeServiceInterface $utilDateTimeService,
        SpySalesReturnQuery $salesReturnQuery
    ) {
        $this->utilDateTimeService = $utilDateTimeService;
        $this->salesReturnQuery = $salesReturnQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_RETURN_ID => 'Return ID',
            static::COL_RETURN_REFERENCE => 'Return Reference',
            static::COL_ORDER_REFERENCE => 'Order Reference',
            static::COL_RETURNED_PRODUCTS => 'Returned Products',
            static::COL_RETURN_DATE => 'Return Date',
            static::COL_STATE => 'State',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setRawColumns([
            static::COL_STATE,
            static::COL_ACTIONS,
        ]);

        $config->setSortable([
            static::COL_RETURN_ID,
            static::COL_RETURN_REFERENCE,
            static::COL_RETURN_DATE,
        ]);

        $config->setSearchable([
            static::COL_RETURN_ID,
            static::COL_RETURN_REFERENCE,
            sprintf('GROUP_CONCAT(DISTINCT %s)', SpySalesOrderTableMap::COL_ORDER_REFERENCE),
        ]);

        $config->setHasSearchableFieldsWithAggregateFunctions(true);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\SalesReturn\Persistence\SpySalesReturn[] $salesReturnEntityCollection */
        $salesReturnEntityCollection = $this->runQuery(
            $this->prepareQuery(),
            $config,
            true
        );

        if (!$salesReturnEntityCollection->count()) {
            return [];
        }

        $returns = $this->mapReturns($salesReturnEntityCollection);

        return $this->expandReturnsWithItemStates($returns);
    }

    /**
     * @module Sales
     *
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery
     */
    protected function prepareQuery(): SpySalesReturnQuery
    {
        /** @var \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery $salesReturnQuery */
        $salesReturnQuery = $this->salesReturnQuery
            ->groupByIdSalesReturn()
            ->useSpySalesReturnItemQuery()
                ->useSpySalesOrderItemQuery()
                    ->joinOrder()
                ->endUse()
            ->endUse()
            ->withColumn(
                sprintf('COUNT(%s)', SpySalesReturnItemTableMap::COL_ID_SALES_RETURN_ITEM),
                static::COL_RETURNED_PRODUCTS
            )
            ->withColumn(
                sprintf('GROUP_CONCAT(DISTINCT %s)', SpySalesOrderTableMap::COL_ORDER_REFERENCE),
                static::COL_ORDER_REFERENCE
            );

        return $salesReturnQuery;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\SalesReturn\Persistence\SpySalesReturn[] $salesReturnEntityCollection
     *
     * @return array
     */
    protected function mapReturns(ObjectCollection $salesReturnEntityCollection): array
    {
        $returns = [];

        foreach ($salesReturnEntityCollection as $salesReturnEntity) {
            $returnData = $salesReturnEntity->toArray();
            $returnData[static::COL_RETURN_DATE] = $this->utilDateTimeService->formatDateTime($salesReturnEntity->getCreatedAt());
            $returnData[static::COL_ACTIONS] = $this->buildLinks($salesReturnEntity);

            $returns[] = $returnData;
        }

        return $returns;
    }

    /**
     * @param array $returns
     *
     * @return array
     */
    protected function expandReturnsWithItemStates(array $returns): array
    {
        foreach ($returns as &$return) {
            $return[static::COL_STATE] = implode(' ', $this->getItemStateLabelsByIdSalesReturn($return[static::COL_RETURN_ID]));
        }

        return $returns;
    }

    /**
     * @module Sales
     * @module Oms
     *
     * @param int $idSalesReturn
     *
     * @return string[]
     */
    protected function getItemStateLabelsByIdSalesReturn(int $idSalesReturn): array
    {
        $salesReturnQuery = clone $this->salesReturnQuery;

         $states = $salesReturnQuery
            ->clear()
             ->filterByIdSalesReturn($idSalesReturn)
             ->useSpySalesReturnItemQuery()
                ->useSpySalesOrderItemQuery()
                    ->joinState()
                    ->withColumn(
                        sprintf('DISTINCT %s', SpyOmsOrderItemStateTableMap::COL_NAME),
                        static::COL_STATE
                    )
                ->endUse()
            ->endUse()
            ->select(static::COL_STATE)
            ->find()
            ->toArray();

         $stateLabels = [];

        foreach ($states as $state) {
            $stateLabels[] = $this->generateLabel(ucfirst($state), static::ITEM_STATE_TO_LABEL_CLASS_MAPPING[$state] ?? 'label-info');
        }

         return $stateLabels;
    }

    /**
     * @param \Orm\Zed\SalesReturn\Persistence\SpySalesReturn $salesReturnEntity
     *
     * @return string
     */
    protected function buildLinks(SpySalesReturn $salesReturnEntity): string
    {
        $buttons = [];

        $buttons[] = $this->generateViewButton(
            Url::generate(static::ROUTE_VIEW_RETURN, [
                static::PARAM_ID_SALES_RETURN => $salesReturnEntity->getIdSalesReturn(),
            ]),
            'View'
        );

        $buttons[] = $this->generateViewButton(
            Url::generate(static::ROUTE_PRINT_SLIP, [
                static::PARAM_ID_SALES_RETURN => $salesReturnEntity->getIdSalesReturn(),
            ]),
            'Print Slip',
            [
                'icon' => '',
                'class' => 'btn-create',
            ]
        );

        return implode(' ', $buttons);
    }
}
