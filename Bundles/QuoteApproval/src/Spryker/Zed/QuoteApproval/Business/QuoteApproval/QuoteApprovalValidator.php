<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApproval;

use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Spryker\Shared\QuoteApproval\Plugin\Permission\ApproveQuotePermissionPlugin;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface;

class QuoteApprovalValidator implements QuoteApprovalValidatorInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(
        QuoteApprovalToQuoteFacadeInterface $quoteFacade
    ) {
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     *
     * @return bool
     */
    public function canUpdateQuoteApprovalRequest(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer, QuoteApprovalTransfer $quoteApprovalTransfer): bool
    {
        return $this->isValidQuoteApprovalRequest($quoteApprovalRequestTransfer, $quoteApprovalTransfer)
            && $this->hasUpdateQuoteApprovalPermissions($quoteApprovalTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     *
     * @return bool
     */
    public function canDeleteQuoteApprovalRequest(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer, QuoteApprovalTransfer $quoteApprovalTransfer): bool
    {
        return $this->isValidQuoteApprovalRequest($quoteApprovalRequestTransfer, $quoteApprovalTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     *
     * @return bool
     */
    protected function isValidQuoteApprovalRequest(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer, QuoteApprovalTransfer $quoteApprovalTransfer): bool
    {
        if ($quoteApprovalTransfer->getStatus() !== QuoteApprovalConfig::STATUS_WAITING) {
            return false;
        }

        if ($quoteApprovalTransfer->getFkCompanyUser() !== $quoteApprovalRequestTransfer->getFkCompanyUser()) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     *
     * @return bool
     */
    protected function hasUpdateQuoteApprovalPermissions(QuoteApprovalTransfer $quoteApprovalTransfer): bool
    {
        $quoteResponseTransfer = $this->quoteFacade->findQuoteById($quoteApprovalTransfer->getFkQuote());

        if (!$this->can(ApproveQuotePermissionPlugin::KEY, $quoteResponseTransfer->getQuoteTransfer())) {
            return false;
        }

        return true;
    }
}
