<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnPageSearch\Persistence;

use Generated\Shared\Transfer\ReturnReasonPageSearchTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchPersistenceFactory getFactory()
 */
class SalesReturnPageSearchEntityManager extends AbstractEntityManager implements SalesReturnPageSearchEntityManagerInterface
{
    /**
     * @param int[] $returnReasonIds
     *
     * @return void
     */
    public function deleteReturnReasonSearchByReturnReasonIds(array $returnReasonIds): void
    {
        if (!$returnReasonIds) {
            return;
        }

        $this->getFactory()
            ->getSalesReturnReasonPageSearchPropelQuery()
            ->filterByFkSalesReturnReason_In($returnReasonIds)
            ->find()
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonPageSearchTransfer $returnReasonPageSearchTransfer
     *
     * @return void
     */
    public function saveReturnReasonSearchPageSearch(ReturnReasonPageSearchTransfer $returnReasonPageSearchTransfer): void
    {
        $returnReasonPageSearchEntity = $this->getFactory()
            ->getSalesReturnReasonPageSearchPropelQuery()
            ->filterByIdSalesReturnReasonPageSearch($returnReasonPageSearchTransfer->getIdSalesReturnReasonPageSearch())
            ->findOneOrCreate();

        $returnReasonPageSearchEntity->fromArray(
            $returnReasonPageSearchTransfer->toArray()
        );

        $returnReasonPageSearchEntity->setFkSalesReturnReason($returnReasonPageSearchTransfer->getIdSalesReturnReason());

        $returnReasonPageSearchEntity->save();
    }
}
