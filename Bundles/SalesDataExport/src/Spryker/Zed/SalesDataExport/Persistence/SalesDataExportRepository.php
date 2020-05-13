<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Persistence;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
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
    public const FILTER_CRITERIA_KEY_STORE_NAME = 'order_store';
    public const FILTER_CRITERIA_KEY_ORDER_CREATED_AT = 'order_created_at';
    public const FILTER_CRITERIA_KEY_ORDER_UPDATED_AT = 'order_updated_at';
    public const FILTER_CRITERIA_PARAM_DATE_FROM = 'from';
    public const FILTER_CRITERIA_PARAM_DATE_TO = 'to';

    public const PROPEL_CRITERIA_BETWEEN_MIN = 'min';
    public const PROPEL_CRITERIA_BETWEEN_MAX = 'max';

    /**
     * @module Country
     * @module Locale
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    public function getOrderData(DataExportConfigurationTransfer $dataExportConfigurationTransfer, int $offset, int $limit): DataExportBatchTransfer
    {
        $selectedColumns = $this->getSalesOrderSelectedColumns($dataExportConfigurationTransfer);
        $dataExportBatchTransfer = (new DataExportBatchTransfer())
            ->setOffset($offset)
            ->setFields(array_flip($selectedColumns))
            ->setData([]);

        $salesOrderQuery = $this->getFactory()->getSalesOrderPropelQuery()
            ->joinLocale()
            ->joinOrderTotal()
            ->leftJoinBillingAddress()
            ->useBillingAddressQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinCountry()
                ->leftJoinRegion()
            ->endUse()
            ->orderByIdSalesOrder()
            ->offset($offset)
            ->limit($limit);

        $selectedColumns[SpySalesOrderTableMap::COL_ID_SALES_ORDER] = SpySalesOrderTableMap::COL_ID_SALES_ORDER;

        $salesOrderQuery = $this->applyFilterCriteriaToSalesOrderQuery($dataExportConfigurationTransfer->getFilterCriteria(), $salesOrderQuery);
        $salesOrderQuery->select($selectedColumns);

        $salesOrderData = $salesOrderQuery->find()
            ->getArrayCopy(SpySalesOrderTableMap::COL_ID_SALES_ORDER);

        if ($salesOrderData === []) {
            return $dataExportBatchTransfer;
        }

        $salesOrderIds = array_keys($salesOrderData);
        $salesOrderCommentTransfers = $this->getCommentsByOrderId($salesOrderIds);
        foreach ($salesOrderIds as $idSalesOrder) {
            $salesOrderData[$idSalesOrder][SalesOrderMapper::KEY_ORDER_COMMENTS] = $salesOrderCommentTransfers[$idSalesOrder] ?? [];
            unset($salesOrderData[$idSalesOrder][SpySalesOrderTableMap::COL_ID_SALES_ORDER]);
        }

        $data = $this->getFactory()
            ->createSalesOrderMapper()
            ->mapSalesOrderDataByField($salesOrderData);

        return $dataExportBatchTransfer->setData($data);
    }

    /**
     * @module Country
     * @module Oms
     * @module Shipment
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    public function getOrderItemData(DataExportConfigurationTransfer $dataExportConfigurationTransfer, int $offset, int $limit): DataExportBatchTransfer
    {
        $selectedColumns = $this->getSalesOrderItemSelectedColumns($dataExportConfigurationTransfer);
        $dataExportBatchTransfer = (new DataExportBatchTransfer())
            ->setOffset($offset)
            ->setFields(array_flip($selectedColumns))
            ->setData([]);

        $salesOrderItemQuery = $this->getFactory()
            ->getSalesOrderItemPropelQuery()
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
            ->orderByFkSalesOrder()
            ->offset($offset)
            ->limit($limit);

        $salesOrderItemQuery = $this->applyFilterCriteriaToSalesOrderItemQuery(
            $dataExportConfigurationTransfer->getFilterCriteria(),
            $salesOrderItemQuery
        );

        $salesOrderItemQuery->select($selectedColumns);

        $salesOrderItemData = $salesOrderItemQuery->find()->getArrayCopy();

        if ($salesOrderItemData === []) {
            return $dataExportBatchTransfer;
        }

        $data = $this->getFactory()
            ->createSalesOrderItemMapper()
            ->mapSalesOrderItemDataByField($salesOrderItemData);

        return $dataExportBatchTransfer->setData($data);
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    public function getOrderExpenseData(DataExportConfigurationTransfer $dataExportConfigurationTransfer, int $offset, int $limit): DataExportBatchTransfer
    {
        $selectedColumns = $this->getSalesExpenseSelectedColumns($dataExportConfigurationTransfer);
        $dataExportBatchTransfer = (new DataExportBatchTransfer())
            ->setOffset($offset)
            ->setFields(array_flip($selectedColumns))
            ->setData([]);

        $salesExpenseQuery = SpySalesExpenseQuery::create()
            ->joinOrder()
            ->leftJoinSpySalesShipment()
            ->orderByFkSalesOrder()
            ->offset($offset)
            ->limit($limit);

        $salesExpenseQuery = $this->applyFilterCriteriaToSalesExpenseQuery(
            $dataExportConfigurationTransfer->getFilterCriteria(),
            $salesExpenseQuery
        );

        $salesExpenseQuery->select($selectedColumns);
        $orderExpenseData = $salesExpenseQuery->find()->getArrayCopy();

        $data = $this->getFactory()
            ->createSalesExpenseMapper()
            ->mapSalesExpenseDataByField($orderExpenseData);

        return $dataExportBatchTransfer->setData($data);
    }

    /**
     * @param array $filterCriteria
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function applyFilterCriteriaToSalesOrderQuery(array $filterCriteria, SpySalesOrderQuery $salesOrderQuery): SpySalesOrderQuery
    {
        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_STORE_NAME])) {
            $salesOrderQuery->filterByStore_In($filterCriteria[static::FILTER_CRITERIA_KEY_STORE_NAME]);
        }

        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT])) {
            $salesOrderQuery->filterByCreatedAt_Between([
                static::PROPEL_CRITERIA_BETWEEN_MIN => $filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT][static::FILTER_CRITERIA_PARAM_DATE_FROM],
                static::PROPEL_CRITERIA_BETWEEN_MAX => $filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT][static::FILTER_CRITERIA_PARAM_DATE_TO],
            ]);
        }

        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_UPDATED_AT])) {
            $salesOrderQuery->filterByUpdatedAt_Between([
                static::PROPEL_CRITERIA_BETWEEN_MIN => $filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_UPDATED_AT][static::FILTER_CRITERIA_PARAM_DATE_FROM],
                static::PROPEL_CRITERIA_BETWEEN_MAX => $filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_UPDATED_AT][static::FILTER_CRITERIA_PARAM_DATE_TO],
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
            $salesOrderItemQuery
                ->useOrderQuery()
                    ->filterByStore_In($filterCriteria[static::FILTER_CRITERIA_KEY_STORE_NAME])
                ->endUse();
        }

        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT])) {
            $salesOrderItemQuery->filterByCreatedAt_Between([
                static::PROPEL_CRITERIA_BETWEEN_MIN => $filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT][static::FILTER_CRITERIA_PARAM_DATE_FROM],
                static::PROPEL_CRITERIA_BETWEEN_MAX => $filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT][static::FILTER_CRITERIA_PARAM_DATE_TO],
            ]);
        }

        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_UPDATED_AT])) {
            $salesOrderItemQuery->filterByUpdatedAt_Between([
                static::PROPEL_CRITERIA_BETWEEN_MIN => $filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_UPDATED_AT][static::FILTER_CRITERIA_PARAM_DATE_FROM],
                static::PROPEL_CRITERIA_BETWEEN_MAX => $filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_UPDATED_AT][static::FILTER_CRITERIA_PARAM_DATE_TO],
            ]);
        }

        return $salesOrderItemQuery;
    }

    /**
     * @param array $filterCriteria
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpenseQuery $salesExpenseQuery
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpenseQuery
     */
    protected function applyFilterCriteriaToSalesExpenseQuery(array $filterCriteria, SpySalesExpenseQuery $salesExpenseQuery): SpySalesExpenseQuery
    {
        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_STORE_NAME])) {
            $salesExpenseQuery
                ->useOrderQuery()
                    ->filterByStore_In($filterCriteria[static::FILTER_CRITERIA_KEY_STORE_NAME])
                ->endUse();
        }

        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT])) {
            $salesExpenseQuery
                ->useOrderQuery()
                    ->filterByCreatedAt_Between([
                        static::PROPEL_CRITERIA_BETWEEN_MIN => $filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT][static::FILTER_CRITERIA_PARAM_DATE_FROM],
                        static::PROPEL_CRITERIA_BETWEEN_MAX => $filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_CREATED_AT][static::FILTER_CRITERIA_PARAM_DATE_TO],
                    ])
                ->endUse();
        }

        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_UPDATED_AT])) {
            $salesExpenseQuery
                ->useOrderQuery()
                    ->filterByUpdatedAt_Between([
                        static::PROPEL_CRITERIA_BETWEEN_MIN => $filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_UPDATED_AT][static::FILTER_CRITERIA_PARAM_DATE_FROM],
                        static::PROPEL_CRITERIA_BETWEEN_MAX => $filterCriteria[static::FILTER_CRITERIA_KEY_ORDER_UPDATED_AT][static::FILTER_CRITERIA_PARAM_DATE_TO],
                    ])
                ->endUse();
        }

        return $salesExpenseQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return array<string, string>
     */
    protected function getSalesOrderSelectedColumns(DataExportConfigurationTransfer $dataExportConfigurationTransfer): array
    {
        $fieldMapping = $this->getFactory()
            ->createSalesOrderMapper()
            ->getFieldMapping();

        $selectedFields = array_intersect_key($fieldMapping, array_flip($dataExportConfigurationTransfer->getFields()));

        return $selectedFields;
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return array<string, string>
     */
    public function getSalesOrderItemSelectedColumns(DataExportConfigurationTransfer $dataExportConfigurationTransfer): array
    {
        $fieldMapping = $this->getFactory()
            ->createSalesOrderItemMapper()
            ->getFieldMapping();

        return array_intersect_key($fieldMapping, array_flip($dataExportConfigurationTransfer->getFields()));
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return array<string, string>
     */
    public function getSalesExpenseSelectedColumns(DataExportConfigurationTransfer $dataExportConfigurationTransfer): array
    {
        $fieldMapping = $this->getFactory()
            ->createSalesExpenseMapper()
            ->getFieldMapping();

        return array_intersect_key($fieldMapping, array_flip($dataExportConfigurationTransfer->getFields()));
    }

    /**
     * @param int[] $salesOrderIds
     *
     * @return \Generated\Shared\Transfer\CommentTransfer[]
     */
    public function getCommentsByOrderId(array $salesOrderIds): array
    {
        $salesOrderCommentEntities = SpySalesOrderCommentQuery::create()
            ->filterByFkSalesOrder_In($salesOrderIds)
            ->find();

        if ($salesOrderCommentEntities->count() === 0) {
            return [];
        }

        return $this->getFactory()
            ->createSalesOrderCommentMapper()
            ->mapSalesOrderCommentEntitiesToCommentTransfersByIdSalesOrder($salesOrderCommentEntities, []);
    }
}
