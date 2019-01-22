<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApproval;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestValidationResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteApproval\Plugin\Permission\ApproveQuotePermissionPlugin;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Shared\QuoteApproval\QuoteStatus\QuoteStatusCalculatorInterface;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyUserFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface;

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
     * @var \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface
     */
    protected $quoteApprovalRepository;

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Shared\QuoteApproval\QuoteStatus\QuoteStatusCalculatorInterface $quoteStatusCalculator
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface $quoteApprovalRepository
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(
        QuoteApprovalToQuoteFacadeInterface $quoteFacade,
        QuoteStatusCalculatorInterface $quoteStatusCalculator,
        QuoteApprovalRepositoryInterface $quoteApprovalRepository,
        QuoteApprovalToCompanyUserFacadeInterface $companyUserFacade
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->quoteStatusCalculator = $quoteStatusCalculator;
        $this->quoteApprovalRepository = $quoteApprovalRepository;
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalRequestValidationResponseTransfer
     */
    public function validateQuoteApprovalCreateRequest(QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer): QuoteApprovalRequestValidationResponseTransfer
    {
        $this->assertQuoteApprovalCreateRequestValid($quoteApprovalCreateRequestTransfer);
        $quoteTransfer = $this->getQuoteById($quoteApprovalCreateRequestTransfer->getIdQuote());

        if (!$this->isQuoteOwner($quoteTransfer, $quoteApprovalCreateRequestTransfer->getRequestSenderCompanyUserId())) {
            return $this->createNotSuccessfullValidationResponseTransfer();
        }

        if (!$this->isApproverCanApproveQuote($quoteTransfer, $quoteApprovalCreateRequestTransfer->getApproverCompanyUserId())) {
            return $this->createNotSuccessfullValidationResponseTransfer();
        }

        if (!$this->isQuoteInCorrectStatus($quoteTransfer)) {
            return $this->createNotSuccessfullValidationResponseTransfer();
        }

        return $this->createSuccessfullValidationResponseTransfer()
            ->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer $quoteApprovalRemoveRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalRequestValidationResponseTransfer
     */
    public function validateQuoteApprovalRemoveRequest(QuoteApprovalRemoveRequestTransfer $quoteApprovalRemoveRequestTransfer): QuoteApprovalRequestValidationResponseTransfer
    {
        $quoteTransfer = $this->findQuoteByIdQuoteApproval($quoteApprovalRemoveRequestTransfer->getIdQuoteApproval());

        if (!$this->isQuoteOwner($quoteTransfer, $quoteApprovalRemoveRequestTransfer->getIdCompanyUser())
            && !$this->isRemoveRequestSentByApprover($quoteApprovalRemoveRequestTransfer)
        ) {
            return $this->createNotSuccessfullValidationResponseTransfer();
        }

        return $this->createSuccessfullValidationResponseTransfer()
            ->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalRequestValidationResponseTransfer
     */
    public function validateQuoteApprovalRequest(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalRequestValidationResponseTransfer
    {
        $this->assertQuoteApprovalRequestValid($quoteApprovalRequestTransfer);
        $quoteApprovalTransfer = $this->quoteApprovalRepository
            ->findQuoteApprovalById($quoteApprovalRequestTransfer->getIdQuoteApproval());
        $quoteTransfer = $this->findQuoteByIdQuoteApproval($quoteApprovalTransfer->getIdQuoteApproval());

        if ($quoteApprovalTransfer->getStatus() !== QuoteApprovalConfig::STATUS_WAITING) {
            return $this->createNotSuccessfullValidationResponseTransfer();
        }

        if ($quoteApprovalTransfer->getFkCompanyUser() !== $quoteApprovalRequestTransfer->getFkCompanyUser()) {
            return $this->createNotSuccessfullValidationResponseTransfer();
        }

        if (!$this->isApproverCanApproveQuote($quoteTransfer, $quoteApprovalRequestTransfer->getFkCompanyUser())) {
            return $this->createNotSuccessfullValidationResponseTransfer();
        }

        return $this->createSuccessfullValidationResponseTransfer()
            ->setQuote($quoteTransfer)
            ->setQuoteApproval($quoteApprovalTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return void
     */
    protected function assertQuoteApprovalRequestValid(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): void
    {
        $quoteApprovalRequestTransfer->requireFkCompanyUser()
            ->requireIdQuoteApproval();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer
     *
     * @return void
     */
    protected function assertQuoteApprovalCreateRequestValid(QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer): void
    {
        $quoteApprovalCreateRequestTransfer->requireApproverCompanyUserId()
            ->requireRequestSenderCompanyUserId()
            ->requireIdQuote();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteApprovalRequestValidationResponseTransfer
     */
    protected function createNotSuccessfullValidationResponseTransfer(): QuoteApprovalRequestValidationResponseTransfer
    {
        $quoteApprovalRequestValidationResponseTransfer = new QuoteApprovalRequestValidationResponseTransfer();
        $quoteApprovalRequestValidationResponseTransfer->setIsSuccessful(false);

        return $quoteApprovalRequestValidationResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteApprovalRequestValidationResponseTransfer
     */
    protected function createSuccessfullValidationResponseTransfer(): QuoteApprovalRequestValidationResponseTransfer
    {
        $quoteApprovalRequestValidationResponseTransfer = new QuoteApprovalRequestValidationResponseTransfer();
        $quoteApprovalRequestValidationResponseTransfer->setIsSuccessful(true);

        return $quoteApprovalRequestValidationResponseTransfer;
    }

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuoteById(int $idQuote): QuoteTransfer
    {
        $quoteResponseTransfer = $this->quoteFacade->findQuoteById($idQuote);
        $quoteResponseTransfer->requireQuoteTransfer();
        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $quoteTransfer->setCustomer(
            (new CustomerTransfer())->setCustomerReference($quoteTransfer->getCustomerReference())
        );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer $quoteApprovalRemoveRequestTransfer
     *
     * @return bool
     */
    protected function isRemoveRequestSentByApprover(QuoteApprovalRemoveRequestTransfer $quoteApprovalRemoveRequestTransfer): bool
    {
        $quoteApprovalTransfer = $this->quoteApprovalRepository->findQuoteApprovalById(
            $quoteApprovalRemoveRequestTransfer->getIdQuoteApproval()
        );

        return $quoteApprovalTransfer->getFkCompanyUser() === $quoteApprovalRemoveRequestTransfer->getIdCompanyUser();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idCompanyUser
     *
     * @return bool
     */
    protected function isQuoteOwner(QuoteTransfer $quoteTransfer, int $idCompanyUser): bool
    {
        $companyUserTransfer = $this->companyUserFacade->getCompanyUserById($idCompanyUser);

        return $quoteTransfer->getCustomerReference() === $companyUserTransfer->getCustomer()->getCustomerReference();
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

    /**
     * @param int $idQuoteApproval
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function findQuoteByIdQuoteApproval(int $idQuoteApproval): ?QuoteTransfer
    {
        $idQuote = $this->quoteApprovalRepository->findIdQuoteByIdQuoteApproval($idQuoteApproval);

        if ($idQuote === null) {
            return null;
        }

        return $this->getQuoteById($idQuote);
    }
}
