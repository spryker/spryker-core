<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApproval;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\QuoteApproval\Business\Quote\QuoteLockerInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToSharedCartFacadeInterface;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface;

class QuoteApprovalCreator implements QuoteApprovalCreatorInterface
{
    use TransactionTrait;

    /**
     * @uses SharedCartConfig::PERMISSION_GROUP_READ_ONLY
     */
    protected const PERMISSION_GROUP_READ_ONLY = 'READ_ONLY';
    protected const GLOSSARY_KEY_APPROVAL_CREATED = 'quote_approval.created';

    /**
     * @var \Spryker\Zed\QuoteApproval\Business\Quote\QuoteLockerInterface
     */
    protected $quoteLocker;

    /**
     * @var \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalRequestValidatorInterface
     */
    protected $quoteApprovalRequestValidator;

    /**
     * @var \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface
     */
    protected $quoteApprovalEntityManager;

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToSharedCartFacadeInterface
     */
    protected $sharedCartFacade;

    /**
     * @var \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface
     */
    protected $quoteApprovalRepository;

    /**
     * @param \Spryker\Zed\QuoteApproval\Business\Quote\QuoteLockerInterface $quoteLocker
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToSharedCartFacadeInterface $sharedCartFacade
     * @param \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalRequestValidatorInterface $quoteApprovalRequestValidator
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface $quoteApprovalRepository
     */
    public function __construct(
        QuoteLockerInterface $quoteLocker,
        QuoteApprovalToSharedCartFacadeInterface $sharedCartFacade,
        QuoteApprovalRequestValidatorInterface $quoteApprovalRequestValidator,
        QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager,
        QuoteApprovalRepositoryInterface $quoteApprovalRepository
    ) {
        $this->quoteLocker = $quoteLocker;
        $this->sharedCartFacade = $sharedCartFacade;
        $this->quoteApprovalRequestValidator = $quoteApprovalRequestValidator;
        $this->quoteApprovalEntityManager = $quoteApprovalEntityManager;
        $this->quoteApprovalRepository = $quoteApprovalRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function createQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteApprovalRequestTransfer) {
            return $this->executeCreateQuoteApprovalTransaction($quoteApprovalRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    protected function executeCreateQuoteApprovalTransaction(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        $quoteApprovalResponseTransfer = $this->quoteApprovalRequestValidator
            ->validateQuoteApprovalCreateRequest($quoteApprovalRequestTransfer);

        if (!$quoteApprovalResponseTransfer->getIsSuccessful()) {
            return $quoteApprovalResponseTransfer;
        }

        $quoteApprovalTransfer = $this->executeQuoteApprovalCreation(
            $quoteApprovalResponseTransfer,
            $quoteApprovalRequestTransfer
        );

        return $this->createSuccessfulQuoteApprovalResponseTransfer($quoteApprovalTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer
     */
    protected function executeQuoteApprovalCreation(
        QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer,
        QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
    ): QuoteApprovalTransfer {
        $quoteTransfer = $quoteApprovalResponseTransfer->getQuote();

        $quoteApprovalTransfer = $this->createQuoteApprovalTransfer(
            $quoteTransfer->getIdQuote(),
            $quoteApprovalRequestTransfer->getApproverCompanyUserId()
        );

        $this->quoteApprovalEntityManager->createQuoteApproval($quoteApprovalTransfer);
        $this->quoteLocker->lockQuote($quoteTransfer);
        $this->sharedCartFacade->deleteShareForQuote($quoteTransfer);

        if (!$this->isQuoteOwner($quoteTransfer, $quoteApprovalTransfer->getApprover())) {
            $this->shareQuoteToApprover($quoteApprovalTransfer);
        }

        return $quoteApprovalTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     *
     * @return void
     */
    protected function shareQuoteToApprover(QuoteApprovalTransfer $quoteApprovalTransfer): void
    {
        $quotePermissionGroup = $this->findSharedCartPermissionGroup();

        $shareCartRequestTransfer = (new ShareCartRequestTransfer())
            ->setIdQuote($quoteApprovalTransfer->getFkQuote())
            ->addShareDetail(
                (new ShareDetailTransfer())
                    ->setIdCompanyUser($quoteApprovalTransfer->getApproverCompanyUserId())
                    ->setQuotePermissionGroup($quotePermissionGroup)
            );

        $this->sharedCartFacade->addQuoteCompanyUser($shareCartRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    protected function isQuoteOwner(QuoteTransfer $quoteTransfer, CompanyUserTransfer $companyUserTransfer): bool
    {
        $quoteApproverCustomerReference = $companyUserTransfer->getCustomer()->getCustomerReference();

        return $quoteTransfer->getCustomerReference() === $quoteApproverCustomerReference;
    }

    /**
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer|null
     */
    protected function findSharedCartPermissionGroup(): ?QuotePermissionGroupTransfer
    {
        $criteriaFilterTransfer = (new QuotePermissionGroupCriteriaFilterTransfer())
            ->setName(static::PERMISSION_GROUP_READ_ONLY);

        $quotePermissionGroupResponseTransfer = $this->sharedCartFacade->getQuotePermissionGroupList($criteriaFilterTransfer);
        if (!$quotePermissionGroupResponseTransfer->getIsSuccessful()) {
            return null;
        }

        return $quotePermissionGroupResponseTransfer->getQuotePermissionGroups()->offsetGet(0);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    protected function createSuccessfulQuoteApprovalResponseTransfer(QuoteApprovalTransfer $quoteApprovalTransfer): QuoteApprovalResponseTransfer
    {
        $approverCustomerTransfer = $quoteApprovalTransfer->getApprover()->getCustomer();

        return (new QuoteApprovalResponseTransfer())
            ->setIsSuccessful(true)
            ->setQuote($this->createQuoteWithQuoteApprovals($quoteApprovalTransfer->getFkQuote()))
            ->addMessage(
                $this->createMessageTransfer(
                    static::GLOSSARY_KEY_APPROVAL_CREATED,
                    [
                        '%first_name%' => $approverCustomerTransfer->getFirstName(),
                        '%last_name%' => $approverCustomerTransfer->getLastName(),
                    ]
                )
            );
    }

    /**
     * @param string $message
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(string $message, array $parameters = []): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue($message)
            ->setParameters($parameters);
    }

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteWithQuoteApprovals(int $idQuote): QuoteTransfer
    {
        return (new QuoteTransfer())->setQuoteApprovals(
            new ArrayObject($this->quoteApprovalRepository->getQuoteApprovalsByIdQuote($idQuote))
        );
    }

    /**
     * @param int $idQuote
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer
     */
    protected function createQuoteApprovalTransfer(int $idQuote, int $idCompanyUser): QuoteApprovalTransfer
    {
        return (new QuoteApprovalTransfer())
            ->setStatus(QuoteApprovalConfig::STATUS_WAITING)
            ->setApproverCompanyUserId($idCompanyUser)
            ->setFkQuote($idQuote);
    }
}
