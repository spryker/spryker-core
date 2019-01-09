<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\Model;

use Generated\Shared\Transfer\QuoteApproveRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteApproval\Plugin\Permission\ApproveQuotePermissionPlugin;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Shared\QuoteApproval\StatusCalculator\QuoteApprovalStatusCalculatorInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToPermissionFacadeInterface;

class QuoteApprovalRequestValidator implements QuoteApprovalRequestValidatorInterface
{
    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToPermissionFacadeInterface
     */
    protected $permissionFacade;

    /**
     * @var \Spryker\Shared\QuoteApproval\StatusCalculator\QuoteApprovalStatusCalculatorInterface
     */
    protected $quoteApprovalStatusCalculator;

    /**
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToPermissionFacadeInterface $permissionFacade
     * @param \Spryker\Shared\QuoteApproval\StatusCalculator\QuoteApprovalStatusCalculatorInterface $quoteApprovalStatusCalculator
     */
    public function __construct(
        QuoteApprovalToPermissionFacadeInterface $permissionFacade,
        QuoteApprovalStatusCalculatorInterface $quoteApprovalStatusCalculator
    ) {
        $this->permissionFacade = $permissionFacade;
        $this->quoteApprovalStatusCalculator = $quoteApprovalStatusCalculator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApproveRequestTransfer $quoteApproveRequestTransfer
     *
     * @return bool
     */
    public function isApproveRequestValid(QuoteApproveRequestTransfer $quoteApproveRequestTransfer): bool
    {
        return $this->isRequestSentByQuoteOwner($quoteApproveRequestTransfer)
            && $this->isApproverCanApproveQuote(
                $quoteApproveRequestTransfer->getQuote(),
                $quoteApproveRequestTransfer->getIdApprover()
            )
            && $this->isQuoteInCorrectStatus($quoteApproveRequestTransfer->getQuote());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApproveRequestTransfer $quoteApproveRequestTransfer
     *
     * @return bool
     */
    protected function isRequestSentByQuoteOwner(QuoteApproveRequestTransfer $quoteApproveRequestTransfer): bool
    {
        $requestSender = $quoteApproveRequestTransfer->getCustomer();
        $quoteOwner = $quoteApproveRequestTransfer->getQuote()->getCustomer();

        return $requestSender->getCustomerReference() === $quoteOwner->getCustomerReference();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idCompanyUser
     *
     * @return bool
     */
    protected function isApproverCanApproveQuote(QuoteTransfer $quoteTransfer, int $idCompanyUser): bool
    {
        return $this->permissionFacade->can(
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
            $this->quoteApprovalStatusCalculator->calculateQuoteStatus($quoteTransfer),
            [null, QuoteApprovalConfig::STATUS_DECLINED]
        );
    }
}
