<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApprovalWriter;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface;

class QuoteApprovalWriter implements QuoteApprovalWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface
     */
    protected $quoteApproavalEntityManager;

    /**
     * @var \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface
     */
    protected $quoteApprovalRepository;

    /**
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface $quoteApprovalRepository
     */
    public function __construct(
        QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager,
        QuoteApprovalRepositoryInterface $quoteApprovalRepository
    ) {
        $this->quoteApproavalEntityManager = $quoteApprovalEntityManager;
        $this->quoteApprovalRepository = $quoteApprovalRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function updateApprovals(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer) {
            return $this->executeUpdateApprovalsTransaction($quoteTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executeUpdateApprovalsTransaction(QuoteTransfer $quoteTransfer)
    {
        $this->removeOldQuoteApprovals($quoteTransfer);
        $this->saveQuoteApprovals($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function saveQuoteApprovals(QuoteTransfer $quoteTransfer): void
    {
        foreach ($quoteTransfer->getApprovals() as $approval) {
            $this->quoteApproavalEntityManager->saveQuoteApproval($approval, $quoteTransfer->getIdQuote());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function removeOldQuoteApprovals(QuoteTransfer $quoteTransfer): void
    {
        $quoteApprovalIds = $this->quoteApprovalRepository
            ->findQuoteApprovalIdCollectionByIdQuote($quoteTransfer->getIdQuote());

        foreach ($quoteTransfer->getApprovals() as $approval) {
            $idQuoteApproval = $approval->getIdQuoteApproval();

            if ($idQuoteApproval === null) {
                continue;
            }

            unset($quoteApprovalIds[array_search($idQuoteApproval, $quoteApprovalIds)]);
        }

        $this->quoteApproavalEntityManager->deleteQuoteApprovalByIds($quoteApprovalIds);
    }
}
