<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Persistence;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrderCommentQuery;
use Orm\Zed\Sales\Persistence\Map\SpySalesExpenseTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesShipmentTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper\MerchantSalesOrderMapper;

/**
 * @method \Spryker\Zed\MerchantSalesOrderDataExport\Persistence\MerchantSalesOrderDataExportPersistenceFactory getFactory()
 */
class MerchantSalesOrderDataExportRepository extends AbstractRepository implements MerchantSalesOrderDataExportRepositoryInterface
{
    public const FILTER_CRITERIA_KEY_STORE_NAME = 'store_name';
    public const FILTER_CRITERIA_KEY_MERCHANT_NAME = 'merchant_name';
    public const FILTER_CRITERIA_KEY_MERCHANT_ORDER_CREATED_AT = 'merchant_order_created_at';
    public const FILTER_CRITERIA_KEY_MERCHANT_ORDER_UPDATED_AT = 'merchant_order_updated_at';

    public const FILTER_CRITERIA_PARAM_OFFSET = 'offset';
    public const FILTER_CRITERIA_PARAM_LIMIT = 'limit';

    public const FILTER_CRITERIA_PARAM_DATE_FROM = 'from';
    public const FILTER_CRITERIA_PARAM_DATE_TO = 'to';

    public const PROPEL_CRITERIA_BETWEEN_MIN = 'min';
    public const PROPEL_CRITERIA_BETWEEN_MAX = 'max';

    /**
     * @module Metchant
     *
     * @return string[]
     */
    public function getMerchantNames(): array
    {
        return $merchantNames = $this->getFactory()->getMerchantPropelQuery()
            ->select([SpyMerchantTableMap::COL_NAME])
            ->find()
            ->toArray();
    }

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

        $hasComments = in_array(MerchantSalesOrderMapper::KEY_MERCHANT_ORDER_COMMENTS, $dataExportConfigurationTransfer->getFields(), true);
        if ($hasComments) {
            $selectedFields[MerchantSalesOrderMapper::KEY_MERCHANT_ORDER_COMMENTS] = MerchantSalesOrderMapper::KEY_MERCHANT_ORDER_COMMENTS;
            $selectedColumns[SpyMerchantSalesOrderTableMap::COL_FK_SALES_ORDER] = SpyMerchantSalesOrderTableMap::COL_FK_SALES_ORDER;
        }
        $filterCriteria = $dataExportConfigurationTransfer->getFilterCriteria();
        $offset = $filterCriteria[static::FILTER_CRITERIA_PARAM_OFFSET];
        $limit = $filterCriteria[static::FILTER_CRITERIA_PARAM_LIMIT];

        $dataExportBatchTransfer = $this->getDataExportBatchTransfer($offset, $selectedFields);

        $merchantSalesOrderQuery = $this->buildMerchantSalesOrderBaseQuery($offset, $limit);
        $merchantSalesOrderQuery = $this->applyFilterCriteriaToMerchantSalesOrderQuery(
            $dataExportConfigurationTransfer->getFilterCriteria(),
            $merchantSalesOrderQuery
        );

        // TODO: what if merchant have no orders
        $merchantSalesOrderQuery->select($selectedColumns);

        $merchantSalesOrderData = $merchantSalesOrderQuery->find()->getArrayCopy();

        if ($merchantSalesOrderData === []) {
            return $dataExportBatchTransfer;
        }

        if ($hasComments) {
            $salesOrderIds = array_column($merchantSalesOrderData, SpyMerchantSalesOrderTableMap::COL_FK_SALES_ORDER);
            $merchantSalesOrderCommentTransfers = $this->getCommentsByOrderId($salesOrderIds);

            foreach ($salesOrderIds as $salesOrderDataKey => $idSalesOrder) {
                $merchantSalesOrderData[$salesOrderDataKey][MerchantSalesOrderMapper::KEY_MERCHANT_ORDER_COMMENTS] = $merchantSalesOrderCommentTransfers[$idSalesOrder] ?? null;
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
        $offset = $filterCriteria[static::FILTER_CRITERIA_PARAM_OFFSET];
        $limit = $filterCriteria[static::FILTER_CRITERIA_PARAM_LIMIT];

        $dataExportBatchTransfer = $this->getDataExportBatchTransfer($offset, $selectedFields);

        $merchantSalesOrderItemQuery = $this->buildMerchantSalesOrderItemBaseQuery($offset, $limit);
        $merchantSalesOrderItemQuery = $this->applyFilterCriteriaToMerchantSalesOrderItemQuery(
            $dataExportConfigurationTransfer->getFilterCriteria(),
            $merchantSalesOrderItemQuery
        );

        $merchantSalesOrderItemQuery->select($selectedColumns);

        $merchantSalesOrderItemData = $merchantSalesOrderItemQuery->find()->toArray();

        if ($merchantSalesOrderItemData === []) {
            return $dataExportBatchTransfer;
        }

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
        $offset = $filterCriteria[static::FILTER_CRITERIA_PARAM_OFFSET];
        $limit = $filterCriteria[static::FILTER_CRITERIA_PARAM_LIMIT];

        $dataExportBatchTransfer = $this->getDataExportBatchTransfer($offset, $selectedFields);

        $merchantSalesOrderQuery = $this->buildMerchantSalesOrderWithExpenseBaseQuery($offset, $limit);
        $merchantSalesOrderQuery = $this->applyFilterCriteriaToMerchantSalesOrderQuery(
            $dataExportConfigurationTransfer->getFilterCriteria(),
            $merchantSalesOrderQuery
        );

        // TODO what if merchant have no orders

        $merchantSalesOrderQuery->select($selectedColumns);
        $merchantOrderExpenseData = $merchantSalesOrderQuery->find()->toArray();

        if ($merchantOrderExpenseData === []) {
            return $dataExportBatchTransfer;
        }

        $data = $this->getFactory()
            ->createMerchantSalesExpenseMapper()
            ->mapMerchantSalesExpenseDataByField($merchantOrderExpenseData);

        return $dataExportBatchTransfer->setData($data);
    }

    /**
     * @param array $filterCriteria
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function applyFilterCriteriaToMerchantSalesOrderQuery(
        array $filterCriteria,
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
    ): SpyMerchantSalesOrderQuery {
        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_NAME])) {
            $spyMerchant = $this->getFactory()
                ->getMerchantPropelQuery()
                ->findOneByName($filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_NAME]);

            if ($spyMerchant) {
                $merchantSalesOrderQuery->filterByMerchantReference($spyMerchant->getMerchantReference());

                //TODO: if we do not have such a merchant
            }
        }

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
     * @param array $filterCriteria
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery $merchantSalesOrderItemQuery
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery
     */
    protected function applyFilterCriteriaToMerchantSalesOrderItemQuery(
        array $filterCriteria,
        SpyMerchantSalesOrderItemQuery $merchantSalesOrderItemQuery
    ): SpyMerchantSalesOrderItemQuery {
        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_NAME])) {
            $spyMerchant = $this->getFactory()
                ->getMerchantPropelQuery()
                ->findOneByName($filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_NAME]);

            if ($spyMerchant) {
                $merchantSalesOrderItemQuery
                    ->useMerchantSalesOderQuery()
                      ->filterByMerchantReference($spyMerchant->getMerchantReference())
                    ->endUse();
            }
        }

        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_STORE_NAME])) {
            $merchantSalesOrderItemQuery
                ->useSalesOrderItemQuery()
                    ->useOrderQuery()
                        ->filterByStore($filterCriteria[static::FILTER_CRITERIA_KEY_STORE_NAME], Criteria::IN)
                    ->endUse()
                ->endUse();
        }

        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_CREATED_AT])) {
            $merchantSalesOrderItemQuery
                ->useMerchantSalesOderQuery()
                    ->filterByCreatedAt_Between([
                        static::PROPEL_CRITERIA_BETWEEN_MIN => $filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_CREATED_AT][static::FILTER_CRITERIA_PARAM_DATE_FROM],
                        static::PROPEL_CRITERIA_BETWEEN_MAX => $filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_CREATED_AT][static::FILTER_CRITERIA_PARAM_DATE_TO],
                    ])
                ->endUse();
        }

        if (isset($filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_UPDATED_AT])) {
            $merchantSalesOrderItemQuery
                ->useMerchantSalesOderQuery()
                    ->filterByUpdatedAt_Between([
                        static::PROPEL_CRITERIA_BETWEEN_MIN => $filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_UPDATED_AT][static::FILTER_CRITERIA_PARAM_DATE_FROM],
                        static::PROPEL_CRITERIA_BETWEEN_MAX => $filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_UPDATED_AT][static::FILTER_CRITERIA_PARAM_DATE_TO],
                     ])
                ->endUse();
        }

        return $merchantSalesOrderItemQuery;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function buildMerchantSalesOrderBaseQuery(int $offset, int $limit): SpyMerchantSalesOrderQuery
    {
        return $this->getFactory()
            ->getMerchantSalesOrderPropelQuery()
            ->leftJoinMerchantSalesOrderTotal()
            ->joinOrder()
            ->useOrderQuery(null, Criteria::LEFT_JOIN)
                ->joinLocale()
                ->leftJoinBillingAddress()
                ->useBillingAddressQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinCountry()
                    ->leftJoinRegion()
                 ->endUse()
                ->orderByIdSalesOrder()
            ->endUse()
            ->orderByIdMerchantSalesOrder()
            ->offset($offset)
            ->limit($limit);
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery
     */
    protected function buildMerchantSalesOrderItemBaseQuery(int $offset, int $limit): SpyMerchantSalesOrderItemQuery
    {
        return $this->getFactory()
            ->getMerchantSalesOrderItemPropelQuery()
            ->leftJoinWithMerchantSalesOder()
            ->leftJoinWithStateMachineItemState()
            ->useStateMachineItemStateQuery()
                ->leftJoinWithProcess()
            ->endUse()
            ->joinWithSalesOrderItem()
            ->useSalesOrderItemQuery()
                ->leftJoinSalesOrderItemBundle()
                ->leftJoinWithSpySalesShipment()
                ->useSpySalesShipmentQuery()
                    ->leftJoinWithSpySalesOrderAddress()
                    ->useSpySalesOrderAddressQuery()
                        ->leftJoinWithCountry()
                        ->leftJoinRegion()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->orderByIdMerchantSalesOrderItem()
            ->offset($offset)
            ->limit($limit);
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function buildMerchantSalesOrderWithExpenseBaseQuery(int $offset, int $limit): SpyMerchantSalesOrderQuery
    {
        return $this->getFactory()
            ->getMerchantSalesOrderPropelQuery()
            ->joinWithOrder()
            ->useOrderQuery()
                ->leftJoinWithSpySalesShipment()
                ->leftJoinWithExpense()
                ->addJoinCondition('SpySalesShipment', sprintf('%s = %s', SpySalesShipmentTableMap::COL_MERCHANT_REFERENCE, SpyMerchantSalesOrderTableMap::COL_MERCHANT_REFERENCE))
                ->addJoinCondition('Expense', sprintf('%s = %s', SpySalesExpenseTableMap::COL_MERCHANT_REFERENCE, SpyMerchantSalesOrderTableMap::COL_MERCHANT_REFERENCE))
            ->endUse();
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
    public function getCommentsByOrderId(array $salesOrderIds): array
    {
        $salesOrderCommentEntities = SpySalesOrderCommentQuery::create()
            ->filterByFkSalesOrder_In($salesOrderIds)
            ->find();

        if ($salesOrderCommentEntities->count() === 0) {
            return [];
        }

        return $this->getFactory()
            ->createMerchantSalesOrderCommentMapper()
            ->mapMerchantSalesOrderCommentEntitiesToCommentTransfersByIdSalesOrder($salesOrderCommentEntities, []);
    }

    /***
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
}
