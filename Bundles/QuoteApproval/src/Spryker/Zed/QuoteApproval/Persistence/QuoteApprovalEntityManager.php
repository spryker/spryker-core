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
    public function updateQuoteApproval(QuoteApprovalTransfer $quoteApprovalTransfer): QuoteApprovalTransfer
    {
        $spyQuoteApproval = $this->getFactory()
            ->createSpyQuoteApprovalQuery()
            ->findOneByIdQuoteApproval($quoteApprovalTransfer->getIdQuoteApproval());

        $spyQuoteApproval = $this->getFactory()
            ->createQuoteApprovalMapper()
            ->mapQuoteApprovalTransferToEntity($quoteApprovalTransfer, $spyQuoteApproval);

        $spyQuoteApproval->save();

        return $quoteApprovalTransfer;
    }

    /**
     * @param int $idQuoteApproval
     *
     * @return void
     */
    public function deleteQuoteApprovalById(int $idQuoteApproval): void
    {
        $this->getFactory()
            ->createSpyQuoteApprovalQuery()
            ->filterByIdQuoteApproval($idQuoteApproval)
            ->delete();
    }
}
