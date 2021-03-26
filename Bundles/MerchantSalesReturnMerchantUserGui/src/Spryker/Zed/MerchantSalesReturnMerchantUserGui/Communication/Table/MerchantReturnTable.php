<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Table;

use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\SalesReturn\Persistence\Map\SpySalesReturnItemTableMap;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturn;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery;
use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineItemStateTableMap;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Service\MerchantSalesReturnMerchantUserGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\MerchantSalesReturnMerchantUserGuiConfig;

class MerchantReturnTable extends AbstractTable
{
    protected const COL_RETURN_ID = 'id_sales_return';
    protected const COL_RETURN_REFERENCE = 'return_reference';
    protected const COL_ORDER_REFERENCE = 'order_reference';
    protected const COL_RETURNED_PRODUCTS = 'returned_products';
    protected const COL_MERCHANT_SALES_ORDER_REFERENCE = 'merchant_sales_order_reference';
    protected const COL_RETURN_DATE = 'created_at';
    protected const COL_STATE = 'state';
    protected const COL_ACTIONS = 'actions';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller\DetailController::indexAction()
     */
    protected const ROUTE_DETAIL = '/merchant-sales-return-merchant-user-gui/detail';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\ReturnSlipController::indexAction()
     */
    protected const ROUTE_RETURN_SLIP = '/sales-return-gui/return-slip';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller\DetailController::PARAM_ID_RETURN
     */
    protected const PARAM_ID_RETURN = 'id-return';

    /**
     * @var \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Service\MerchantSalesReturnMerchantUserGuiToUtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @var \Spryker\Zed\MerchantSalesReturnMerchantUserGui\MerchantSalesReturnMerchantUserGuiConfig
     */
    protected $merchantSalesReturnMerchantUserGuiConfig;

    /**
     * @var \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery|mixed[]
     */
    protected $salesReturnQuery;

    /**
     * @var \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @param \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Service\MerchantSalesReturnMerchantUserGuiToUtilDateTimeServiceInterface $utilDateTimeService
     * @param \Spryker\Zed\MerchantSalesReturnMerchantUserGui\MerchantSalesReturnMerchantUserGuiConfig $merchantSalesReturnMerchantUserGuiConfig
     * @param \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery|mixed[] $salesReturnQuery
     * @param \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(
        MerchantSalesReturnMerchantUserGuiToUtilDateTimeServiceInterface $utilDateTimeService,
        MerchantSalesReturnMerchantUserGuiConfig $merchantSalesReturnMerchantUserGuiConfig,
        SpySalesReturnQuery $salesReturnQuery,
        MerchantSalesReturnMerchantUserGuiToMerchantUserFacadeInterface $merchantUserFacade
    ) {
        $this->utilDateTimeService = $utilDateTimeService;
        $this->salesReturnQuery = $salesReturnQuery;
        $this->merchantSalesReturnMerchantUserGuiConfig = $merchantSalesReturnMerchantUserGuiConfig;
        $this->merchantUserFacade = $merchantUserFacade;
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
            static::COL_MERCHANT_SALES_ORDER_REFERENCE => 'Marketplace Order Reference',
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
            static::COL_ORDER_REFERENCE,
            static::COL_MERCHANT_SALES_ORDER_REFERENCE,
        ]);

        $config->setHasSearchableFieldsWithAggregateFunctions(true);
        $config->setDefaultSortField(static::COL_RETURN_ID, TableConfiguration::SORT_DESC);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return string[]
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
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery|mixed[]
     */
    protected function prepareQuery(): SpySalesReturnQuery
    {
        $merchantTransfer = $this->merchantUserFacade
            ->getCurrentMerchantUser()
            ->requireMerchant()
            ->getMerchantOrFail();

        $merchantReference = $merchantTransfer
            ->requireMerchantReference()
            ->getMerchantReference();

        /** @var \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery|mixed[] $salesReturnQuery */
        $salesReturnQuery = $this->salesReturnQuery
            ->groupByIdSalesReturn()
            ->useSpySalesReturnItemQuery()
                ->useSpySalesOrderItemQuery()
                    ->filterByMerchantReference($merchantReference)
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
     * @phpstan-return array<int, mixed>
     *
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\SalesReturn\Persistence\SpySalesReturn[] $salesReturnEntityCollection
     *
     * @return mixed[]
     */
    protected function mapReturns(ObjectCollection $salesReturnEntityCollection): array
    {
        $returns = [];

        foreach ($salesReturnEntityCollection as $salesReturnEntity) {
            $returnData = $salesReturnEntity->toArray();
            $returnData[static::COL_RETURN_DATE] = $this->utilDateTimeService->formatDateTime($salesReturnEntity->getCreatedAt() ?? '');
            $returnData[static::COL_ACTIONS] = $this->buildLinks($salesReturnEntity);

            $returns[] = $returnData;
        }

        return $returns;
    }

    /**
     * @phpstan-return array<string, string>
     *
     * @param mixed[] $returns
     *
     * @return string[]
     */
    protected function expandReturnsWithItemStates(array $returns): array
    {
        foreach ($returns as &$return) {
            $return[static::COL_STATE] = implode(' ', $this->getItemStateLabelsByIdSalesReturn($return[static::COL_RETURN_ID]));
        }

        return $returns;
    }

    /**
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
                    ->useMerchantSalesOrderItemQuery()
                        ->joinWithStateMachineItemState()
                        ->withColumn(
                            sprintf('GROUP_CONCAT(DISTINCT %s)', SpyStateMachineItemStateTableMap::COL_NAME),
                            static::COL_STATE
                        )
                    ->endUse()
                ->endUse()
            ->endUse()
            ->select(static::COL_STATE)
            ->find()
            ->toArray();

        $stateLabels = [];

        foreach ($states as $state) {
            $stateLabels[] = $this->generateLabel(ucfirst($state), $this->merchantSalesReturnMerchantUserGuiConfig->getItemStateToLabelClassMapping()[$state] ?? 'label-default');
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
            Url::generate(static::ROUTE_DETAIL, [
                static::PARAM_ID_RETURN => $salesReturnEntity->getIdSalesReturn(),
            ]),
            'View'
        );

        $buttons[] = $this->generateViewButton(
            Url::generate(static::ROUTE_RETURN_SLIP, [
                static::PARAM_ID_RETURN => $salesReturnEntity->getIdSalesReturn(),
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
