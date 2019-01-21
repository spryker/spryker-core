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
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCustomerFacadeInterface;
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
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Shared\QuoteApproval\QuoteStatus\QuoteStatusCalculatorInterface $quoteStatusCalculator
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface $quoteApprovalRepository
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCustomerFacadeInterface $customerFacade
     */
    public function __construct(
        QuoteApprovalToQuoteFacadeInterface $quoteFacade,
        QuoteStatusCalculatorInterface $quoteStatusCalculator,
        QuoteApprovalRepositoryInterface $quoteApprovalRepository,
        QuoteApprovalToCustomerFacadeInterface $customerFacade
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->quoteStatusCalculator = $quoteStatusCalculator;
        $this->quoteApprovalRepository = $quoteApprovalRepository;
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalRequestValidationResponseTransfer
     */
    public function validateQuoteApprovalCreateRequest(QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer): QuoteApprovalRequestValidationResponseTransfer
    {
        $quoteApprovalCreateRequestTransfer->requireCustomerReference()
            ->requireIdCompanyUser()
            ->requireIdQuote();

        $quoteTransfer = $this->findQuoteById($quoteApprovalCreateRequestTransfer->getIdQuote());

        $quoteApprovalRequestValidationResponseTransfer = new QuoteApprovalRequestValidationResponseTransfer();
        $quoteApprovalRequestValidationResponseTransfer->setIsSuccessful(false)
             ->setQuote($quoteTransfer);

        if (!$quoteTransfer) {
            return $quoteApprovalRequestValidationResponseTransfer;
        }

        if (!$this->isQuoteOwner($quoteTransfer, $quoteApprovalCreateRequestTransfer->getCustomerReference())) {
            return $quoteApprovalRequestValidationResponseTransfer;
        }

        if (!$this->isApproverCanApproveQuote($quoteTransfer, $quoteApprovalCreateRequestTransfer->getIdCompanyUser())) {
            return $quoteApprovalRequestValidationResponseTransfer;
        }

        if (!$this->isQuoteInCorrectStatus($quoteTransfer)) {
            return $quoteApprovalRequestValidationResponseTransfer;
        }

        $quoteApprovalRequestValidationResponseTransfer->setIsSuccessful(true);

        return $quoteApprovalRequestValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer $quoteApprovalRemoveRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalRequestValidationResponseTransfer
     */
    public function validateQuoteApprovalRemoveReqeust(QuoteApprovalRemoveRequestTransfer $quoteApprovalRemoveRequestTransfer): QuoteApprovalRequestValidationResponseTransfer
    {
        $quoteTransfer = $this->findQuoteByIdQuoteApproval($quoteApprovalRemoveRequestTransfer->getIdQuoteApproval());

        $quoteApprovalRequestValidationResponseTransfer = new QuoteApprovalRequestValidationResponseTransfer();
        $quoteApprovalRequestValidationResponseTransfer->setIsSuccessful(false)
            ->setQuote($quoteTransfer);

        if (!$quoteTransfer) {
            return $quoteApprovalRequestValidationResponseTransfer;
        }

        if (!$this->isQuoteOwner($quoteTransfer, $quoteApprovalRemoveRequestTransfer->getCustomerReference())
            && !$this->isRemoveRequestSentByApprover($quoteApprovalRemoveRequestTransfer)
        ) {
            return $quoteApprovalRequestValidationResponseTransfer;
        }

        $quoteApprovalRequestValidationResponseTransfer->setIsSuccessful(true);

        return $quoteApprovalRequestValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalRequestValidationResponseTransfer
     */
    public function validateQuoteApprovalRequest(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalRequestValidationResponseTransfer
    {
        $quoteApprovalRequestTransfer->requireFkCompanyUser()
            ->requireIdQuoteApproval();

        $quoteApprovalTransfer = $this->quoteApprovalRepository
            ->findQuoteApprovalById($quoteApprovalRequestTransfer->getIdQuoteApproval());

        $quoteApprovalRequestValidationResponseTransfer = new QuoteApprovalRequestValidationResponseTransfer();
        $quoteApprovalRequestValidationResponseTransfer->setIsSuccessful(false);

        if ($quoteApprovalTransfer->getStatus() !== QuoteApprovalConfig::STATUS_WAITING) {
            return $quoteApprovalRequestValidationResponseTransfer;
        }

        if ($quoteApprovalTransfer->getFkCompanyUser() !== $quoteApprovalRequestTransfer->getFkCompanyUser()) {
            return $quoteApprovalRequestValidationResponseTransfer;
        }

        $quoteTransfer = $this->findQuoteByIdQuoteApproval($quoteApprovalTransfer->getIdQuoteApproval());

        if (!$this->isApproverCanApproveQuote($quoteTransfer, $quoteApprovalRequestTransfer->getFkCompanyUser())) {
            return $quoteApprovalRequestValidationResponseTransfer;
        }

        $quoteApprovalRequestValidationResponseTransfer->setQuote($quoteTransfer)
            ->setQuoteApproval($quoteApprovalTransfer)
            ->setIsSuccessful(true);

        return $quoteApprovalRequestValidationResponseTransfer;
    }

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function findQuoteById(int $idQuote): ?QuoteTransfer
    {
        $quoteTransfer = $this->quoteFacade->findQuoteById($idQuote)->getQuoteTransfer();

        if (!$quoteTransfer) {
            return null;
        }

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
        $customerReponseTransfer = $this->customerFacade->findCustomerByReference(
            $quoteApprovalRemoveRequestTransfer->getCustomerReference()
        );

        $customerReponseTransfer->requireCustomerTransfer();

        $quoteApprovalTransfer = $this->quoteApprovalRepository->findQuoteApprovalById(
            $quoteApprovalRemoveRequestTransfer->getIdQuoteApproval()
        );

        $companyUserId = $customerReponseTransfer->getCustomerTransfer()
            ->getFkUser();

        return $quoteApprovalTransfer->getFkCompanyUser() === $companyUserId;
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

        return $this->findQuoteById($idQuote);
    }
}
