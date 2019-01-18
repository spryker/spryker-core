<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApproval;

use Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteApproval\Plugin\Permission\ApproveQuotePermissionPlugin;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Shared\QuoteApproval\QuoteStatus\QuoteStatusCalculatorInterface;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface;

class QuoteApprovalRequestValidator implements QuoteApprovalRequestValidatorInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Shared\QuoteApproval\QuoteStatus\QuoteStatusCalculatorInterface
     */
    protected $quoteStatusCalculator;

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Shared\QuoteApproval\QuoteStatus\QuoteStatusCalculatorInterface $quoteStatusCalculator
     */
    public function __construct(
        QuoteApprovalToQuoteFacadeInterface $quoteFacade,
        QuoteStatusCalculatorInterface $quoteStatusCalculator
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->quoteStatusCalculator = $quoteStatusCalculator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer
     *
     * @return bool
     */
    public function isCreateQuoteApprovalRequestValid(QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer): bool
    {
        $quoteApprovalCreateRequestTransfer->requireCustomerReference()
            ->requireIdCompanyUser()
            ->requireIdQuote();

        $quoteTransfer = $this->findQuoteById($quoteApprovalCreateRequestTransfer->getIdQuote());

        if (!$quoteTransfer) {
            return false;
        }

        if (!$this->isQuoteOwner($quoteTransfer, $quoteApprovalCreateRequestTransfer->getCustomerReference())) {
            return false;
        }

        if (!$this->isApproverCanApproveQuote($quoteTransfer, $quoteApprovalCreateRequestTransfer->getIdCompanyUser())) {
            return false;
        }

        if (!$this->isQuoteInCorrectStatus($quoteTransfer)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function findQuoteById(int $idQuote): ?QuoteTransfer
    {
        return $this->quoteFacade->findQuoteById($idQuote)->getQuoteTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $customerReference
     *
     * @return bool
     */
    protected function isQuoteOwner(QuoteTransfer $quoteTransfer, string $customerReference): bool
    {
        return $quoteTransfer->getCustomerReference() === $customerReference;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idCompanyUser
     *
     * @return bool
     */
    protected function isApproverCanApproveQuote(QuoteTransfer $quoteTransfer, int $idCompanyUser): bool
    {
        return $this->can(
            ApproveQuotePermissionPlugin::KEY,
            $idCompanyUser,
            $quoteTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteInCorrectStatus(QuoteTransfer $quoteTransfer): bool
    {
        return in_array(
            $this->quoteStatusCalculator->calculateQuoteStatus($quoteTransfer),
            [null, QuoteApprovalConfig::STATUS_DECLINED]
        );
    }
}
