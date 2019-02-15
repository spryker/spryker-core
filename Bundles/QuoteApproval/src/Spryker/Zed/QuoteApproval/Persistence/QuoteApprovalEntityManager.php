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
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer
     */
    public function saveQuoteApproval(QuoteApprovalTransfer $quoteApprovalTransfer): QuoteApprovalTransfer
    {
        $quoteApprovalEntity = $this->getFactory()
            ->createQuoteApprovalPropelQuery()
            ->filterByIdQuoteApproval($quoteApprovalTransfer->getIdQuoteApproval())
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
     * @param int $idQuoteApproval
     *
     * @return void
     */
    public function deleteQuoteApprovalById(int $idQuoteApproval): void
    {
        $this->getFactory()
            ->createQuoteApprovalPropelQuery()
            ->filterByIdQuoteApproval($idQuoteApproval)
            ->delete();
    }

    /**
     * @param int $idQuote
     *
     * @return void
     */
    public function deleteApprovalsByIdQuote(int $idQuote): void
    {
        $this->getFactory()
            ->createQuoteApprovalPropelQuery()
            ->filterByFkQuote($idQuote)
            ->delete();
    }
}
