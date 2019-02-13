<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\QuoteApproval\QuoteApprovalFactory getFactory()
 */
class QuoteApprovalClient extends AbstractClient implements QuoteApprovalClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    public function calculateQuoteStatus(QuoteTransfer $quoteTransfer): ?string
    {
        return $this->getFactory()
            ->createQuoteStatusCalculator()
            ->calculateQuoteStatus($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function createQuoteApproval(QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->getFactory()->createQuoteApprovalStub()->createQuoteApproval($quoteApprovalCreateRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer $quoteApprovalRemoveRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function removeQuoteApproval(QuoteApprovalRemoveRequestTransfer $quoteApprovalRemoveRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->getFactory()->createQuoteApprovalStub()->removeQuoteApproval($quoteApprovalRemoveRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getQuoteApproversList(QuoteTransfer $quoteTransfer): CompanyUserCollectionTransfer
    {
        return $this->getFactory()->createQuoteApprovalStub()->getQuoteApproversList($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteRequireApproval(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()->createQuoteStatusChecker()->isQuoteRequireApproval($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteWaitingForApproval(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()->createQuoteStatusChecker()->isQuoteWaitingForApproval($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteApproved(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()->createQuoteStatusChecker()->isQuoteApproved($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return int|null
     */
    public function calculateApproveQuotePermissionLimit(QuoteTransfer $quoteTransfer, CompanyUserTransfer $companyUserTransfer): ?int
    {
        return $this->getFactory()
            ->createPermissionLimitCalculator()
            ->calculateApproveQuotePermissionLimit($quoteTransfer, $companyUserTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    public function calculatePlaceOrderPermissionLimit(QuoteTransfer $quoteTransfer, CompanyUserTransfer $companyUserTransfer): ?int
    {
        return $this->getFactory()
            ->createPermissionLimitCalculator()
            ->calculatePlaceOrderPermissionLimit($quoteTransfer, $companyUserTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function approveQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->getFactory()
            ->createQuoteApprovalStub()
            ->approveQuoteApproval($quoteApprovalRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function declineQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->getFactory()
            ->createQuoteApprovalStub()
            ->declineQuoteApproval($quoteApprovalRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer|null
     */
    public function findWaitingQuoteApprovalByIdCompanyUser(QuoteTransfer $quoteTransfer, int $idCompanyUser): ?QuoteApprovalTransfer
    {
        return $this->getFactory()
            ->createQuoteApprovalReader()
            ->findWaitingQuoteApprovalByIdCompanyUser($quoteTransfer, $idCompanyUser);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteCanBeApprovedByCurrentCustomer(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()
            ->createQuoteStatusChecker()
            ->isQuoteCanBeApprovedByCurrentCustomer($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idCompanyUser
     *
     * @return bool
     */
    public function hasQuoteApprovalsForCompanyUser(QuoteTransfer $quoteTransfer, int $idCompanyUser): bool
    {
        return $this->getFactory()
            ->createQuoteApprovalReader()
            ->hasQuoteApprovalsForCompanyUser($quoteTransfer, $idCompanyUser);
    }
}
