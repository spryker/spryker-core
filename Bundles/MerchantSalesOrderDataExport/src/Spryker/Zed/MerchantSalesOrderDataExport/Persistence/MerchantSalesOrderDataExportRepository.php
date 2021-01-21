<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Persistence;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Orm\Zed\Sales\Persistence\Map\SpySalesExpenseTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantSalesOrderDataExport\Persistence\MerchantSalesOrderDataExportPersistenceFactory getFactory()
 */
class MerchantSalesOrderDataExportRepository extends AbstractRepository implements MerchantSalesOrderDataExportRepositoryInterface
{
    protected const FILTER_CRITERIA_KEY_STORE_NAME = 'store_name';

    /**
     * @uses \Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper\MerchantSalesOrderMapper::KEY_MERCHANT_NAME
     */
    protected const FILTER_CRITERIA_KEY_MERCHANT_NAME = 'merchant_name';

    /**
     * @uses \Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper\MerchantSalesOrderMapper::KEY_MERCHANT_ORDER_COMMENTS
     */
    protected const KEY_MERCHANT_ORDER_COMMENTS = 'merchant_order_comments';

    protected const FILTER_CRITERIA_KEY_MERCHANT_ORDER_CREATED_AT = 'merchant_order_created_at';
    protected const FILTER_CRITERIA_KEY_MERCHANT_ORDER_UPDATED_AT = 'merchant_order_updated_at';

    protected const FILTER_CRITERIA_PARAM_OFFSET = 'offset';
    protected const FILTER_CRITERIA_PARAM_LIMIT = 'limit';

    protected const FILTER_CRITERIA_PARAM_DATE_FROM = 'from';
    protected const FILTER_CRITERIA_PARAM_DATE_TO = 'to';

    protected const PROPEL_CRITERIA_BETWEEN_MIN = 'min';
    protected const PROPEL_CRITERIA_BETWEEN_MAX = 'max';

    /**
     * @module MerchantSalesOrder
     * @module Sales
     * @module Country
     * @module Locale
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    public function getMerchantOrderData(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportBatchTransfer
    {
        $selectedColumns = $this->getMerchantSalesOrderSelectedColumns($dataExportConfigurationTransfer);
        $selectedFields = array_flip($selectedColumns);

        $hasComments = in_array(static::KEY_MERCHANT_ORDER_COMMENTS, $dataExportConfigurationTransfer->getFields(), true);
        if ($hasComments) {
            $selectedFields[static::KEY_MERCHANT_ORDER_COMMENTS] = static::KEY_MERCHANT_ORDER_COMMENTS;
            $selectedColumns[SpyMerchantSalesOrderTableMap::COL_FK_SALES_ORDER] = SpyMerchantSalesOrderTableMap::COL_FK_SALES_ORDER;
        }

        $filterCriteria = $dataExportConfigurationTransfer->getFilterCriteria();

        $dataExportBatchTransfer = $this->getDataExportBatchTransfer(
            $filterCriteria[static::FILTER_CRITERIA_PARAM_OFFSET],
            $selectedFields
        );

        $merchantSalesOrderQuery = $this->buildMerchantSalesOrderBaseQuery(
            $filterCriteria[static::FILTER_CRITERIA_PARAM_OFFSET],
            $filterCriteria[static::FILTER_CRITERIA_PARAM_LIMIT]
        );

        $merchantSalesOrderQuery = $this->applyFilterCriteriaToMerchantSalesOrderQuery(
            $dataExportConfigurationTransfer->getFilterCriteria(),
            $merchantSalesOrderQuery
        );

        foreach ($selectedColumns as $selectedField => $selectedColumn) {
            $merchantSalesOrderQuery->addAsColumn(sprintf('"%s"', $selectedColumn), $selectedColumn);
        }

        $merchantSalesOrderData = $merchantSalesOrderQuery->find()->toArray();

        if ($merchantSalesOrderData === []) {
            return $dataExportBatchTransfer;
        }

        $merchantSalesOrderData = $this->formatRowItemDataKeys($merchantSalesOrderData);

        if ($hasComments) {
            $salesOrderIds = array_column($merchantSalesOrderData, SpyMerchantSalesOrderTableMap::COL_FK_SALES_ORDER);
            $merchantSalesOrderCommentTransfers = $this->getCommentsByOrderIds($salesOrderIds);

            foreach ($salesOrderIds as $salesOrderDataKey => $idSalesOrder) {
                $merchantSalesOrderData[$salesOrderDataKey][static::KEY_MERCHANT_ORDER_COMMENTS] = $merchantSalesOrderCommentTransfers[$idSalesOrder] ?? null;
                unset($merchantSalesOrderData[$salesOrderDataKey][SpyMerchantSalesOrderTableMap::COL_FK_SALES_ORDER]);
            }
        }

        $data = $this->getFactory()
            ->createMerchantSalesOrderMapper()
            ->mapMerchantSalesOrderDataByField($merchantSalesOrderData, $selectedFields);

        return $dataExportBatchTransfer->setData($data);
    }

    /**
     * @module MerchantSalesOrder
     * @module Sales
     * @module Country
     * @module Shipment
     * @module Oms
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    public function getMerchantOrderItemData(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportBatchTransfer
    {
        $selectedColumns = $this->getMerchantSalesOrderItemSelectedColumns($dataExportConfigurationTransfer);
        $selectedFields = array_flip($selectedColumns);

        $filterCriteria = $dataExportConfigurationTransfer->getFilterCriteria();

        $dataExportBatchTransfer = $this->getDataExportBatchTransfer(
            $filterCriteria[static::FILTER_CRITERIA_PARAM_OFFSET],
            $selectedFields
        );

        $merchantSalesOrderItemQuery = $this->buildMerchantSalesOrderItemBaseQuery(
            $filterCriteria[static::FILTER_CRITERIA_PARAM_OFFSET],
            $filterCriteria[static::FILTER_CRITERIA_PARAM_LIMIT]
        );

        $merchantSalesOrderQuery = $this->applyFilterCriteriaToMerchantSalesOrderQuery(
            $dataExportConfigurationTransfer->getFilterCriteria(),
            $merchantSalesOrderItemQuery->useMerchantSalesOrderQuery()
        );
        $merchantSalesOrderItemQuery = $merchantSalesOrderQuery->useMerchantSalesOrderItemQuery();

        foreach ($selectedColumns as $selectedField => $selectedColumn) {
            $merchantSalesOrderItemQuery->addAsColumn(sprintf('"%s"', $selectedColumn), $selectedColumn);
        }

        $merchantSalesOrderItemData = $merchantSalesOrderItemQuery->find()->toArray();

        if ($merchantSalesOrderItemData === []) {
            return $dataExportBatchTransfer;
        }

        $merchantSalesOrderItemData = $this->formatRowItemDataKeys($merchantSalesOrderItemData);

        $data = $this->getFactory()
            ->createMerchantSalesOrderItemMapper()
            ->mapMerchantSalesOrderItemDataByField($merchantSalesOrderItemData);

        return $dataExportBatchTransfer->setData($data);
    }

    /**
     * @module MerchantSalesOrder
     * @module Sales
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    public function getMerchantOrderExpenseData(
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportBatchTransfer {
        $selectedColumns = $this->getSalesExpenseSelectedColumns($dataExportConfigurationTransfer);
        $selectedFields = array_flip($selectedColumns);

        $filterCriteria = $dataExportConfigurationTransfer->getFilterCriteria();

        $dataExportBatchTransfer = $this->getDataExportBatchTransfer(
            $filterCriteria[static::FILTER_CRITERIA_PARAM_OFFSET],
            $selectedFields
        );

        $merchantSalesOrderQuery = $this->buildMerchantSalesOrderWithExpenseBaseQuery(
            $filterCriteria[static::FILTER_CRITERIA_PARAM_OFFSET],
            $filterCriteria[static::FILTER_CRITERIA_PARAM_LIMIT]
        );

        $merchantSalesOrderQuery = $this->applyFilterCriteriaToMerchantSalesOrderQuery(
            $dataExportConfigurationTransfer->getFilterCriteria(),
            $merchantSalesOrderQuery
        );

        foreach ($selectedColumns as $selectedField => $selectedColumn) {
            $merchantSalesOrderQuery->addAsColumn(sprintf('"%s"', $selectedColumn), $selectedColumn);
        }

        $merchantOrderExpenseData = $merchantSalesOrderQuery->find()->toArray();

        if ($merchantOrderExpenseData === []) {
            return $dataExportBatchTransfer;
        }

        $merchantOrderExpenseData = $this->formatRowItemDataKeys($merchantOrderExpenseData);

        $data = $this->getFactory()
            ->createMerchantSalesExpenseMapper()
            ->mapMerchantSalesExpenseDataByField($merchantOrderExpenseData);

        return $dataExportBatchTransfer->setData($data);
    }

    /**
     * @param mixed[] $filterCriteria
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function applyFilterCriteriaToMerchantSalesOrderQuery(
        array $filterCriteria,
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
    ): SpyMerchantSalesOrderQuery {
        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_STORE_NAME])) {
            $merchantSalesOrderQuery
                ->useOrderQuery()
                    ->filterByStore($filterCriteria[static::FILTER_CRITERIA_KEY_STORE_NAME], Criteria::IN)
                ->endUse();
        }
        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_CREATED_AT])) {
            $merchantSalesOrderQuery->filterByCreatedAt_Between([
                static::PROPEL_CRITERIA_BETWEEN_MIN => $filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_CREATED_AT][static::FILTER_CRITERIA_PARAM_DATE_FROM],
                static::PROPEL_CRITERIA_BETWEEN_MAX => $filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_CREATED_AT][static::FILTER_CRITERIA_PARAM_DATE_TO],
            ]);
        }

        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_UPDATED_AT])) {
            $merchantSalesOrderQuery->filterByUpdatedAt_Between([
                static::PROPEL_CRITERIA_BETWEEN_MIN => $filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_UPDATED_AT][static::FILTER_CRITERIA_PARAM_DATE_FROM],
                static::PROPEL_CRITERIA_BETWEEN_MAX => $filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_UPDATED_AT][static::FILTER_CRITERIA_PARAM_DATE_TO],
            ]);
        }

        return $merchantSalesOrderQuery;
    }

    /**
     * @param mixed[] $filterCriteria
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery $merchantSalesOrderItemQuery
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery
     */
    protected function applyFilterCriteriaToMerchantSalesOrderItemQuery(
        array $filterCriteria,
        SpyMerchantSalesOrderItemQuery $merchantSalesOrderItemQuery
    ): SpyMerchantSalesOrderItemQuery {
        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_STORE_NAME])) {
            $merchantSalesOrderItemQuery
                ->useMerchantSalesOrderQuery()
                    ->useOrderQuery()
                        ->filterByStore($filterCriteria[static::FILTER_CRITERIA_KEY_STORE_NAME], Criteria::IN)
                    ->endUse()
                ->endUse();
        }
        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_CREATED_AT])) {
            $merchantSalesOrderItemQuery
                ->useMerchantSalesOrderQuery()
                    ->filterByCreatedAt_Between([
                        static::PROPEL_CRITERIA_BETWEEN_MIN => $filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_CREATED_AT][static::FILTER_CRITERIA_PARAM_DATE_FROM],
                        static::PROPEL_CRITERIA_BETWEEN_MAX => $filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_CREATED_AT][static::FILTER_CRITERIA_PARAM_DATE_TO],
                    ])
                ->endUse();
        }

        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_UPDATED_AT])) {
            $merchantSalesOrderItemQuery
                ->useMerchantSalesOrderQuery()
                    ->filterByUpdatedAt_Between([
                        static::PROPEL_CRITERIA_BETWEEN_MIN => $filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_UPDATED_AT][static::FILTER_CRITERIA_PARAM_DATE_FROM],
                        static::PROPEL_CRITERIA_BETWEEN_MAX => $filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_UPDATED_AT][static::FILTER_CRITERIA_PARAM_DATE_TO],
                     ])
                ->endUse();
        }

        return $merchantSalesOrderItemQuery;
    }

    /**
     * @phpstan-return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function buildMerchantSalesOrderBaseQuery(int $offset, int $limit): SpyMerchantSalesOrderQuery
    {
        return $this->getFactory()
            ->getMerchantSalesOrderPropelQuery()
            ->orderByMerchantReference()
            ->addJoin(
                SpyMerchantSalesOrderTableMap::COL_MERCHANT_REFERENCE,
                SpyMerchantTableMap::COL_MERCHANT_REFERENCE,
                Criteria::LEFT_JOIN
            )
            ->leftJoinMerchantSalesOrderTotal()
            ->joinOrder()
            ->useOrderQuery()
                ->orderByStore()
                ->joinLocale()
                ->leftJoinBillingAddress()
                ->useBillingAddressQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinCountry()
                    ->leftJoinRegion()
                ->endUse()
            ->endUse()
            ->offset($offset)
            ->limit($limit);
    }

    /**
     * @phpstan-return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery
     */
    protected function buildMerchantSalesOrderItemBaseQuery(int $offset, int $limit): SpyMerchantSalesOrderItemQuery
    {
        return $this->getFactory()
            ->getMerchantSalesOrderItemPropelQuery()
            ->innerJoinMerchantSalesOrder()
            ->useMerchantSalesOrderQuery()
                ->addJoin(
                    SpyMerchantSalesOrderTableMap::COL_MERCHANT_REFERENCE,
                    SpyMerchantTableMap::COL_MERCHANT_REFERENCE,
                    Criteria::LEFT_JOIN
                )
                ->orderByMerchantReference()
                ->innerJoinOrder()
                ->useOrderQuery()
                    ->orderByStore()
                ->endUse()
            ->endUse()
            ->leftJoinStateMachineItemState()
            ->useStateMachineItemStateQuery()
                ->leftJoinProcess()
            ->endUse()
            ->joinSalesOrderItem()
            ->useSalesOrderItemQuery()
                ->leftJoinSalesOrderItemBundle()
                ->leftJoinSpySalesShipment()
                ->useSpySalesShipmentQuery()
                    ->leftJoinSpySalesOrderAddress()
                    ->useSpySalesOrderAddressQuery()
                        ->leftJoinCountry()
                        ->leftJoinRegion()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->offset($offset)
            ->limit($limit);
    }

    /**
     * @phpstan-return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery<\Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder>
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function buildMerchantSalesOrderWithExpenseBaseQuery(int $offset, int $limit): SpyMerchantSalesOrderQuery
    {
        return $merchantSalesOrderQuery = $this->getFactory()
            ->getMerchantSalesOrderPropelQuery()
            ->addJoin(
                SpyMerchantSalesOrderTableMap::COL_MERCHANT_REFERENCE,
                SpyMerchantTableMap::COL_MERCHANT_REFERENCE,
                Criteria::LEFT_JOIN
            )
            ->innerJoinOrder()
            ->useOrderQuery()
                ->leftJoinExpense()
                ->where(SpySalesExpenseTableMap::COL_MERCHANT_REFERENCE . '=' . SpyMerchantSalesOrderTableMap::COL_MERCHANT_REFERENCE)
                ->useExpenseQuery()
                    ->leftJoinSpySalesShipment()
                ->endUse()
                ->orderByStore()
            ->endUse()
            ->orderByMerchantReference()
            ->offset($offset)
            ->limit($limit);
    }

    /**
     * @phpstan-return array<string, string>
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return string[]
     */
    protected function getMerchantSalesOrderSelectedColumns(DataExportConfigurationTransfer $dataExportConfigurationTransfer): array
    {
        $fieldMapping = $this->getFactory()
            ->createMerchantSalesOrderMapper()
            ->getFieldMapping();

        return array_intersect_key($fieldMapping, array_flip($dataExportConfigurationTransfer->getFields()));
    }

    /**
     * @phpstan-return array<string, string>
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return string[]
     */
    public function getMerchantSalesOrderItemSelectedColumns(DataExportConfigurationTransfer $dataExportConfigurationTransfer): array
    {
        $fieldMapping = $this->getFactory()
            ->createMerchantSalesOrderItemMapper()
            ->getFieldMapping();

        return array_intersect_key($fieldMapping, array_flip($dataExportConfigurationTransfer->getFields()));
    }

    /**
     * @phpstan-return array<string, string>
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return string[]
     */
    public function getSalesExpenseSelectedColumns(DataExportConfigurationTransfer $dataExportConfigurationTransfer): array
    {
        $fieldMapping = $this->getFactory()
            ->createMerchantSalesExpenseMapper()
            ->getFieldMapping();

        return array_intersect_key($fieldMapping, array_flip($dataExportConfigurationTransfer->getFields()));
    }

    /**
     * @param int[] $salesOrderIds
     *
     * @return \Generated\Shared\Transfer\CommentTransfer[]
     */
    public function getCommentsByOrderIds(array $salesOrderIds): array
    {
        $salesOrderCommentEntities =  $this->getFactory()
            ->getSalesOrderCommentPropelQuery()
            ->filterByFkSalesOrder_In($salesOrderIds)
            ->find();

        if ($salesOrderCommentEntities->count() === 0) {
            return [];
        }

        return $this->getFactory()
            ->createMerchantSalesOrderCommentMapper()
            ->mapMerchantSalesOrderCommentEntitiesToCommentTransfersByIdSalesOrder($salesOrderCommentEntities, []);
    }

    /**
     * @param int $offset
     * @param string[] $selectedFields
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    protected function getDataExportBatchTransfer(int $offset, array $selectedFields): DataExportBatchTransfer
    {
        return (new DataExportBatchTransfer())
            ->setOffset($offset)
            ->setFields($selectedFields)
            ->setData([]);
    }

    /**
     * @param string[][] $rowItemsData
     *
     * @return string[][]
     */
    protected function formatRowItemDataKeys(array $rowItemsData): array
    {
        foreach ($rowItemsData as $key => $rowItemData) {
            foreach ($rowItemData as $rowKey => $rowValue) {
                $trimmedRowKey = trim($rowKey, '"');
                if ($trimmedRowKey !== $rowKey) {
                    $rowItemData[$trimmedRowKey] = $rowValue;
                    unset($rowItemData[$rowKey]);
                }
            }
            $rowItemsData[$key] = $rowItemData;
        }

        return $rowItemsData;
    }
}
