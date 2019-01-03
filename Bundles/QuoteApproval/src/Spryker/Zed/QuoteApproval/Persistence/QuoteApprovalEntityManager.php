<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Persistence;

use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalPersistenceFactory getFactory()
 */
class QuoteApprovalEntityManager extends AbstractEntityManager implements QuoteApprovalEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     * @param int $idQutoe
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer
     */
    public function saveQuoteApproval(
        QuoteApprovalTransfer $quoteApprovalTransfer,
        int $idQutoe
    ): QuoteApprovalTransfer {
        $quoteApprovalEntity = $this->getFactory()
            ->createQuoteApprovalQuery()
            ->filterByIdQuoteApproval($quoteApprovalTransfer->getIdQuoteApproval())
            ->filterByFkQuote($idQutoe)
            ->findOneOrCreate();

        $quoteApprovalEntity = $this->getFactory()
            ->createQuoteApprovalMapper()
            ->mapQuoteApprovalTransferToEntity(
                $quoteApprovalTransfer,
                $quoteApprovalEntity
            );

        $quoteApprovalEntity->save();

        return $this->getFactory()
            ->createQuoteApprovalMapper()
            ->mapQuoteApprovalEntityToTransfer($quoteApprovalEntity, $quoteApprovalTransfer);
    }

    /**
     * @param array $quoteApprovalIds
     *
     * @return void
     */
    public function deleteQuoteApprovalByIds(array $quoteApprovalIds): void
    {
        $this->getFactory()
            ->createQuoteApprovalQuery()
            ->filterByIdQuoteApproval_In($quoteApprovalIds)
            ->find()
            ->delete();
    }
}
