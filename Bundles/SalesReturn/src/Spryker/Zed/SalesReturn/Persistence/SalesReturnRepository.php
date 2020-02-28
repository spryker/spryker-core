<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Persistence;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesReturn\Persistence\SalesReturnPersistenceFactory getFactory()
 */
class SalesReturnRepository extends AbstractRepository implements SalesReturnRepositoryInterface
{
    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    public function findSalesOrderItemByIdSalesOrder(int $idSalesOrderItem): ?ItemTransfer
    {
        $salesOrderItemEntity = $this->getFactory()
            ->getSalesOrderItemPropelQuery()
            ->filterByIdSalesOrderItem($idSalesOrderItem)
            ->findOne();

        if (!$salesOrderItemEntity) {
            return null;
        }

        return (new ItemTransfer())->fromArray($salesOrderItemEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonFilterTransfer $returnReasonFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnReasonTransfer[]
     */
    public function getReturnReasons(ReturnReasonFilterTransfer $returnReasonFilterTransfer): array
    {
        $returnReasonQuery = $this->getFactory()->getSalesReturnReasonPropelQuery();

        $returnReasonQuery = $this->buildQueryFromCriteria(
            $returnReasonQuery,
            $returnReasonFilterTransfer->getFilter()
        );

        $returnReasonQuery->setFormatter(ModelCriteria::FORMAT_OBJECT);

        return $this->getFactory()
            ->createReturnReasonMapper()
            ->mapReturnReasonEntityCollectionToReturnReasonTransfers($returnReasonQuery->find());
    }

    /**
     * @param string $customerReference
     *
     * @return int
     */
    public function countCustomerReturns(string $customerReference): int
    {
        return $this->getFactory()
            ->getSalesReturnPropelQuery()
            ->filterByCustomerReference($customerReference)
            ->count();
    }
}
