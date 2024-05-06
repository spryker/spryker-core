<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch\Persistence;

use Generated\Shared\Transfer\ReturnReasonSearchTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchPersistenceFactory getFactory()
 */
class SalesReturnSearchEntityManager extends AbstractEntityManager implements SalesReturnSearchEntityManagerInterface
{
    /**
     * @param array<int> $returnReasonIds
     *
     * @return void
     */
    public function deleteReturnReasonSearchByReturnReasonIds(array $returnReasonIds): void
    {
        if (!$returnReasonIds) {
            return;
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $salesReturnReasonSearchCollection */
        $salesReturnReasonSearchCollection = $this->getFactory()
            ->getSalesReturnReasonSearchPropelQuery()
            ->filterByFkSalesReturnReason_In($returnReasonIds)
            ->find();

        $salesReturnReasonSearchCollection->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonSearchTransfer $returnReasonSearchTransfer
     *
     * @return void
     */
    public function saveReturnReasonSearch(ReturnReasonSearchTransfer $returnReasonSearchTransfer): void
    {
        $returnReasonSearchEntity = $this->getFactory()
            ->getSalesReturnReasonSearchPropelQuery()
            ->filterByIdSalesReturnReasonSearch($returnReasonSearchTransfer->getIdSalesReturnReasonSearch())
            ->findOneOrCreate();

        $returnReasonSearchEntity->fromArray(
            $returnReasonSearchTransfer->toArray(),
        );

        $returnReasonSearchEntity->setFkSalesReturnReason($returnReasonSearchTransfer->getIdSalesReturnReason());

        $returnReasonSearchEntity->save();
    }
}
