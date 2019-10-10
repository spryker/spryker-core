<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\PermissionAwareTrait;
use Spryker\Client\QuoteApproval\Permission\ContextProvider\PermissionContextProviderInterface;
use Spryker\Client\QuoteApproval\Plugin\Permission\ApproveQuotePermissionPlugin;
use Spryker\Client\QuoteApproval\Plugin\Permission\PlaceOrderPermissionPlugin;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;

class QuoteStatusChecker implements QuoteStatusCheckerInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Client\QuoteApproval\Quote\QuoteStatusCalculatorInterface
     */
    protected $quoteStatusCalculator;

    /**
     * @var \Spryker\Client\QuoteApproval\Permission\ContextProvider\PermissionContextProviderInterface
     */
    protected $permissionContextProvider;

    /**
     * @param \Spryker\Client\QuoteApproval\Quote\QuoteStatusCalculatorInterface $quoteStatusCalculator
     * @param \Spryker\Client\QuoteApproval\Permission\ContextProvider\PermissionContextProviderInterface $permissionContextProvider
     */
    public function __construct(
        QuoteStatusCalculatorInterface $quoteStatusCalculator,
        PermissionContextProviderInterface $permissionContextProvider
    ) {
        $this->quoteStatusCalculator = $quoteStatusCalculator;
        $this->permissionContextProvider = $permissionContextProvider;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteApprovalRequired(QuoteTransfer $quoteTransfer): bool
    {
        if ($this->isQuoteWaitingForApproval($quoteTransfer)) {
            return true;
        }

        if ($this->can(PlaceOrderPermissionPlugin::KEY, $this->permissionContextProvider->provideContext($quoteTransfer))) {
            return false;
        }

        return !$this->isQuoteApproved($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function canQuoteBeApprovedByCurrentCustomer(QuoteTransfer $quoteTransfer): bool
    {
        return $this->can(ApproveQuotePermissionPlugin::KEY, $this->permissionContextProvider->provideContext($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteWaitingForApproval(QuoteTransfer $quoteTransfer): bool
    {
        $quoteStatus = $this->quoteStatusCalculator
            ->calculateQuoteStatus($quoteTransfer);

        return $quoteStatus === QuoteApprovalConfig::STATUS_WAITING;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteApproved(QuoteTransfer $quoteTransfer): bool
    {
        $quoteTransfer = $this->quoteStatusCalculator
            ->calculateQuoteStatus($quoteTransfer);

        return $quoteTransfer === QuoteApprovalConfig::STATUS_APPROVED;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteDeclined(QuoteTransfer $quoteTransfer): bool
    {
        $quoteStatus = $this->quoteStatusCalculator
            ->calculateQuoteStatus($quoteTransfer);

        return $quoteStatus === QuoteApprovalConfig::STATUS_DECLINED;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteInApprovalProcess(QuoteTransfer $quoteTransfer): bool
    {
        return in_array($this->quoteStatusCalculator->calculateQuoteStatus($quoteTransfer), [
            QuoteApprovalConfig::STATUS_WAITING,
            QuoteApprovalConfig::STATUS_APPROVED,
        ]);
    }
}
