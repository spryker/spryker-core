<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\QuoteApproval\Business\Permission\ContextProvider\PermissionContextProviderInterface;
use Spryker\Zed\QuoteApproval\Communication\Plugin\Permission\PlaceOrderPermissionPlugin;

class QuoteStatusChecker implements QuoteStatusCheckerInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Zed\QuoteApproval\Business\Quote\QuoteStatusCalculatorInterface
     */
    protected $quoteStatusCalculator;

    /**
     * @var \Spryker\Zed\QuoteApproval\Business\Permission\ContextProvider\PermissionContextProviderInterface
     */
    protected $permissionContextProvider;

    /**
     * @param \Spryker\Zed\QuoteApproval\Business\Quote\QuoteStatusCalculatorInterface $quoteStatusCalculator
     * @param \Spryker\Zed\QuoteApproval\Business\Permission\ContextProvider\PermissionContextProviderInterface $permissionContextProvider
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

        if ($this->can(PlaceOrderPermissionPlugin::KEY, $quoteTransfer->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser(), $this->permissionContextProvider->provideContext($quoteTransfer))) {
            return false;
        }

        return !$this->isQuoteApproved($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteWaitingForApproval(QuoteTransfer $quoteTransfer): bool
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
    protected function isQuoteApproved(QuoteTransfer $quoteTransfer): bool
    {
        $quoteTransfer = $this->quoteStatusCalculator
            ->calculateQuoteStatus($quoteTransfer);

        return $quoteTransfer === QuoteApprovalConfig::STATUS_APPROVED;
    }
}
