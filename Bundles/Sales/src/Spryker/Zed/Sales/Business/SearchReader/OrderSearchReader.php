<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\SearchReader;

use Generated\Shared\Transfer\FilterFieldTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\QueryJoinCollectionTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Generated\Shared\Transfer\WhereConditionTransfer;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class OrderSearchReader implements OrderSearchReaderInterface
{
    protected const ORDER_SEARCH_GROUP_TYPE_ORDER_REFERENCE = 'orderReference';
    protected const ORDER_SEARCH_GROUP_TYPE_ITEM_NAME = 'itemName';
    protected const ORDER_SEARCH_GROUP_TYPE_ITEM_SKU = 'itemSku';
    protected const ORDER_SEARCH_GROUP_TYPE_ALL = 'all';

    protected const ORDER_SEARCH_GROUP_MAPPING = [
        self::ORDER_SEARCH_GROUP_TYPE_ORDER_REFERENCE => 'spy_sales_order.order_reference',
        self::ORDER_SEARCH_GROUP_TYPE_ITEM_NAME => 'spy_sales_order_item.name',
        self::ORDER_SEARCH_GROUP_TYPE_ITEM_SKU => 'spy_sales_order_item.sku',
    ];

    protected const RELATION_ITEM = 'Item';

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface
     */
    protected $salesRepository;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     */
    public function __construct(SalesRepositoryInterface $salesRepository)
    {
        $this->salesRepository = $salesRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function searchOrders(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $orderListTransfer = $this->transformFilterFieldsToQueryJoins($orderListTransfer);

        return $this->salesRepository->searchOrders($orderListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function transformFilterFieldsToQueryJoins(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $queryJoinCollectionTransfer = new QueryJoinCollectionTransfer();
        $mappedSearchGroups = $this->getMappedSearchGroups();

        foreach ($orderListTransfer->getFilterFields() as $filterFieldTransfer) {
            $filterFieldType = $filterFieldTransfer->getType();

            if (
                in_array($filterFieldType, $mappedSearchGroups, true)
                || $filterFieldType === static::ORDER_SEARCH_GROUP_TYPE_ALL
            ) {
                $queryJoinCollectionTransfer = $this->addOrderSearchGroupQueryJoin($filterFieldTransfer, $queryJoinCollectionTransfer);

                continue;
            }
        }

        return $orderListTransfer->setQueryJoins($queryJoinCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer $filterFieldTransfer
     * @param \Generated\Shared\Transfer\QueryJoinCollectionTransfer $queryJoinCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QueryJoinCollectionTransfer
     */
    protected function addOrderSearchGroupQueryJoin(
        FilterFieldTransfer $filterFieldTransfer,
        QueryJoinCollectionTransfer $queryJoinCollectionTransfer
    ): QueryJoinCollectionTransfer {
        $queryJoinTransfer = new QueryJoinTransfer();

        $searchGroupType = $filterFieldTransfer->getType();
        $searchGroupValue = $filterFieldTransfer->getValue();

        if ($searchGroupType === static::ORDER_SEARCH_GROUP_TYPE_ORDER_REFERENCE) {
            $queryJoinTransfer->addWhereCondition(
                $this->createWhereConditionTransfer($searchGroupType, $searchGroupValue)
            );

            return $queryJoinCollectionTransfer->addQueryJoin($queryJoinTransfer);
        }

        if (
            $searchGroupType === static::ORDER_SEARCH_GROUP_TYPE_ITEM_NAME
            || $searchGroupType === static::ORDER_SEARCH_GROUP_TYPE_ITEM_SKU
        ) {
            $queryJoinTransfer
                ->setRelation(static::RELATION_ITEM)
                ->addWhereCondition(
                    $this->createWhereConditionTransfer($searchGroupType, $searchGroupValue)
                );

            return $queryJoinCollectionTransfer->addQueryJoin($queryJoinTransfer);
        }

        return $this->addOrderSearchGroupAllQueryJoin($filterFieldTransfer, $queryJoinCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer $filterFieldTransfer
     * @param \Generated\Shared\Transfer\QueryJoinCollectionTransfer $queryJoinCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QueryJoinCollectionTransfer
     */
    protected function addOrderSearchGroupAllQueryJoin(
        FilterFieldTransfer $filterFieldTransfer,
        QueryJoinCollectionTransfer $queryJoinCollectionTransfer
    ): QueryJoinCollectionTransfer {
        $queryJoinTransfer = new QueryJoinTransfer();

        foreach ($this->getMappedSearchGroups() as $searchGroupType) {
            $queryJoinTransfer->addWhereCondition(
                $this->createWhereConditionTransfer($searchGroupType, $filterFieldTransfer->getValue())
            );
        }

        $queryJoinTransfer->setRelation(static::RELATION_ITEM);

        return $queryJoinCollectionTransfer->addQueryJoin($queryJoinTransfer);
    }

    /**
     * @param string $type
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\WhereConditionTransfer
     */
    protected function createWhereConditionTransfer(string $type, string $value): WhereConditionTransfer
    {
        return (new WhereConditionTransfer())
            ->setColumn(static::ORDER_SEARCH_GROUP_MAPPING[$type])
            ->setValue($value);
    }

    /**
     * @return string[]
     */
    protected function getMappedSearchGroups(): array
    {
        return array_keys(static::ORDER_SEARCH_GROUP_MAPPING);
    }
}
