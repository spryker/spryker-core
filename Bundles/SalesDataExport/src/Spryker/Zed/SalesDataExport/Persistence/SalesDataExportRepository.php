<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Persistence;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderCommentQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\SalesDataExport\Persistence\Propel\Mapper\SalesOrderMapper;

/**
 * @method \Spryker\Zed\SalesDataExport\Persistence\SalesDataExportPersistenceFactory getFactory()
 */
class SalesDataExportRepository extends AbstractRepository implements SalesDataExportRepositoryInterface
{
    public const FILTER_CRITERIA_KEY_LOCALE_IS_ACTIVE = 'locale_is_active';
    public const FILTER_CRITERIA_KEY_STORE_NAME = 'store_name';
    public const FILTER_CRITERIA_KEY_ORDER_CREATED_AT = 'order_created_at';
    public const FILTER_CRITERIA_KEY_ORDER_CREATED_AT_FROM = 'from';
    public const FILTER_CRITERIA_KEY_ORDER_CREATED_AT_TO = 'to';

    /**
     * @module Country
     * @module Locale
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getOrdersData(DataExportConfigurationTransfer $dataExportConfigurationTransfer, int $offset, int $limit): array
    {
        $salesOrderQuery = $this->getFactory()->getSalesOrderPropelQuery()
            ->joinLocale()
            ->leftJoinOrderTotal()
            ->leftJoinBillingAddress()
            ->useBillingAddressQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinCountry()
                ->leftJoinRegion()
            ->endUse()
            ->offset($offset)
            ->limit($limit);

        $salesOrderQuery = $this->applyFilterCriteriaToSalesOrderQuery($dataExportConfigurationTransfer->getFilterCriteria(), $salesOrderQuery);
        $salesOrderQuery->select($this->getSalesOrderSelectFields($dataExportConfigurationTransfer));

        $salesOrderData = $salesOrderQuery->find()
            ->getArrayCopy(SpySalesOrderTableMap::COL_ID_SALES_ORDER);

        if ($salesOrderData === []) {
            return [];
        }

        $salesOrderIds = array_keys($salesOrderData);
        $salesOrderCommentTransfers = $this->getCommentsByOrderId($salesOrderIds);
        foreach ($salesOrderIds as $idSalesOrder) {
            $salesOrderData[$idSalesOrder][SalesOrderMapper::KEY_ORDER_COMMENTS] = $salesOrderCommentTransfers[$idSalesOrder] ?? [];
            unset($salesOrderData[$idSalesOrder][SpySalesOrderTableMap::COL_ID_SALES_ORDER]);
        }

        return $this->getFactory()
            ->createSalesOrderMapper()
            ->mapSalesOrderDataToCsvFormattedArray($salesOrderData);
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getOrderItemsData(DataExportConfigurationTransfer $dataExportConfigurationTransfer, int $offset, int $limit) : array
    {
        $salesOrderItemQuery = SpySalesOrderItemQuery::create()
            ->joinOrder()
            ->joinState()
            ->leftJoinProcess()
            ->leftJoinSalesOrderItemBundle()
            ->leftJoinSpySalesShipment()
            ->useSpySalesShipmentQuery()
                ->leftJoinSpySalesOrderAddress()
                ->useSpySalesOrderAddressQuery()
                    ->leftJoinCountry()
                    ->leftJoinRegion()
                ->endUse()
            ->endUse()
            ->offset($offset)
            ->limit($limit);

        $salesOrderItemQuery = $this->applyFilterCriteriaToSalesOrderItemQuery(
            $dataExportConfigurationTransfer->getFilterCriteria(),
            $salesOrderItemQuery
        );

        $salesOrderItemQuery->select($this->getSalesOrderItemSelectFields($dataExportConfigurationTransfer));

        $salesOrderItemData = $salesOrderItemQuery->find()->getArrayCopy();

        if ($salesOrderItemData === []) {
            return [];
        }

        return [
            count($salesOrderItemData) > 0 ? array_keys($salesOrderItemData[0]) : [],
            $salesOrderItemData,
        ];
    }

    /**
     * @param array $filterCriteria
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function applyFilterCriteriaToSalesOrderQuery(array $filterCriteria, SpySalesOrderQuery $salesOrderQuery): SpySalesOrderQuery
    {
        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_LOCALE_IS_ACTIVE])) {
            $salesOrderQuery->useLocaleQuery(null, Criteria::INNER_JOIN)
                ->filterByIsActive(true)
                ->endUse();
        }

        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_STORE_NAME])) {
            $salesOrderQuery->filterByStore_In($filterCriteria[static::FILTER_CRITERIA_KEY_STORE_NAME]);
        }

        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT])) {
            $salesOrderQuery->filterByCreatedAt_Between([
                'min' => $filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT][static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT_FROM],
                'max' => $filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT][static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT_TO]
            ]);
        }

        return $salesOrderQuery;
    }

    /**
     * @param array $filterCriteria
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $salesOrderItemQuery
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected function applyFilterCriteriaToSalesOrderItemQuery(array $filterCriteria, SpySalesOrderItemQuery $salesOrderItemQuery): SpySalesOrderItemQuery
    {
        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_STORE_NAME])) {
            $salesOrderItemQuery->useOrderQuery()
                ->filterByStore_In($filterCriteria[static::FILTER_CRITERIA_KEY_STORE_NAME])
                ->endUse();
        }

        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT])) {
            $salesOrderItemQuery->filterByCreatedAt_Between([
                'min' => $filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT][static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT_FROM],
                'max' => $filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT][static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT_TO]
            ]);
        }

        return $salesOrderItemQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return array<string, string>
     */
    protected function getSalesOrderSelectFields(DataExportConfigurationTransfer $dataExportConfigurationTransfer): array
    {
        $csvMapping = $this->getFactory()
            ->createSalesOrderMapper()
            ->getCsvMapping();

        $selectedFields = array_intersect_key($csvMapping, array_flip($dataExportConfigurationTransfer->getFields()));
        $selectedFields[SpySalesOrderTableMap::COL_ID_SALES_ORDER] = SpySalesOrderTableMap::COL_ID_SALES_ORDER;

        return $selectedFields;
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return array<string, string>
     */
    protected function getSalesOrderItemSelectFields(DataExportConfigurationTransfer $dataExportConfigurationTransfer): array
    {
        $csvMapping = $this->getFactory()
            ->createSalesOrderItemMapper()
            ->getCsvMapping();

        return array_intersect_key($csvMapping, array_flip($dataExportConfigurationTransfer->getFields()));
    }

    /**
     * @param int[] $salesOrderIds
     *
     * @return \Generated\Shared\Transfer\CommentTransfer[]
     */
    protected function getCommentsByOrderId(array $salesOrderIds): array
    {
        $salesOrderCommentEntities = SpySalesOrderCommentQuery::create()
            ->filterByFkSalesOrder_In($salesOrderIds)
            ->find();

        if ($salesOrderCommentEntities->count() === 0) {
            return [];
        }

        return $this->getFactory()
            ->createSalesOrderCommentMapper()
            ->mapSalesOrderCommentEntitiesToCommentTransfersGropedByIdSalesOrder($salesOrderCommentEntities, []);
    }
}
