<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApproval;

use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Spryker\Shared\QuoteApproval\Plugin\Permission\ApproveQuotePermissionPlugin;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface;

class QuoteApprovalWriter implements QuoteApprovalWriterInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface
     */
    protected $quoteApprovalEntityManager;

    /**
     * @var \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface
     */
    protected $quoteApprovalRepository;

    /**
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface $quoteApprovalRepository
     */
    public function __construct(
        QuoteApprovalToQuoteFacadeInterface $quoteFacade,
        QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager,
        QuoteApprovalRepositoryInterface $quoteApprovalRepository
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->quoteApprovalEntityManager = $quoteApprovalEntityManager;
        $this->quoteApprovalRepository = $quoteApprovalRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function approveQuote(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        $quoteApprovalTransfer = $this->quoteApprovalRepository
            ->findQuoteApprovalById($quoteApprovalRequestTransfer->getIdQuoteApproval());

        $quoteApprovalResponseTransfer = (new QuoteApprovalResponseTransfer())
            ->setIsSuccessful(false);

        $quoteTransfer = $this->quoteFacade->findQuoteById($quoteApprovalTransfer->getFkQuote());

        //todo: check the status of quote

        if (!$this->can(ApproveQuotePermissionPlugin::KEY, $quoteTransfer)) {
            return $quoteApprovalResponseTransfer;
        }

        if ($quoteApprovalTransfer->getStatus() !== QuoteApprovalConfig::STATUS_WAITING) {
            return $quoteApprovalResponseTransfer;
        }

        if ($quoteApprovalTransfer->getFkCompanyUser() != $quoteApprovalRequestTransfer->getFkCompanyUser()) {
            return $quoteApprovalResponseTransfer;
        }

        $quoteApprovalTransfer->setStatus(QuoteApprovalConfig::STATUS_APPROVED);
        $quoteApprovalTransfer = $this->quoteApprovalEntityManager->updateQuoteApproval($quoteApprovalTransfer);

        $quoteApprovalResponseTransfer
            ->setQuoteApproval($quoteApprovalTransfer)
            ->setIsSuccessful(true);

        return $quoteApprovalResponseTransfer;
    }
}
