<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\PermissionAwareTrait;
use Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToPermissionClientInterface;
use Spryker\Shared\QuoteApproval\Plugin\Permission\PlaceOrderPermissionPlugin;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Shared\QuoteApproval\QuoteStatus\QuoteStatusCalculatorInterface;

class QuoteStatusChecker implements QuoteStatusCheckerInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToPermissionClientInterface
     */
    protected $permissionClient;

    /**
     * @var \Spryker\Shared\QuoteApproval\QuoteStatus\QuoteStatusCalculatorInterface
     */
    protected $quoteStatusCalculator;

    /**
     * @param \Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToPermissionClientInterface $permissionClient
     * @param \Spryker\Shared\QuoteApproval\QuoteStatus\QuoteStatusCalculatorInterface $quoteStatusCalculator
     */
    public function __construct(
        QuoteApprovalToPermissionClientInterface $permissionClient,
        QuoteStatusCalculatorInterface $quoteStatusCalculator
    ) {
        $this->permissionClient = $permissionClient;
        $this->quoteStatusCalculator = $quoteStatusCalculator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteRequireApproval(QuoteTransfer $quoteTransfer): bool
    {
        if (!$this->permissionClient->findCustomerPermissionByKey(PlaceOrderPermissionPlugin::KEY)) {
            return false;
        }

        if ($this->isQuoteWaitingForApproval($quoteTransfer)) {
            return true;
        }

        if ($this->can(PlaceOrderPermissionPlugin::KEY, $quoteTransfer)) {
            return false;
        }

        return !$this->isQuoteApproved($quoteTransfer);
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
}
