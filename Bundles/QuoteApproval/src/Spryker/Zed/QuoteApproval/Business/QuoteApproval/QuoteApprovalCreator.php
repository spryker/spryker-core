<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApproval;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\QuoteApproval\Business\Quote\QuoteLockerInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToSharedCartFacadeInterface;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface;

class QuoteApprovalCreator implements QuoteApprovalCreatorInterface
{
    use TransactionTrait;

    protected const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';
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
     * @param \Spryker\Zed\QuoteApproval\Business\Quote\QuoteLockerInterface $quoteLocker
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToSharedCartFacadeInterface $sharedCartFacade
     * @param \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalRequestValidatorInterface $quoteApprovalRequestValidator
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager
     */
    public function __construct(
        QuoteLockerInterface $quoteLocker,
        QuoteApprovalToSharedCartFacadeInterface $sharedCartFacade,
        QuoteApprovalRequestValidatorInterface $quoteApprovalRequestValidator,
        QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager
    ) {
        $this->quoteLocker = $quoteLocker;
        $this->sharedCartFacade = $sharedCartFacade;
        $this->quoteApprovalRequestValidator = $quoteApprovalRequestValidator;
        $this->quoteApprovalEntityManager = $quoteApprovalEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function createQuoteApproval(QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteApprovalCreateRequestTransfer) {
            return $this->executeCreateQuoteApprovalTransaction($quoteApprovalCreateRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    protected function executeCreateQuoteApprovalTransaction(QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer): QuoteApprovalResponseTransfer
    {
        $quoteApprovalRequestValidationReponseTransfer = $this->quoteApprovalRequestValidator
            ->validateQuoteApprovalCreateRequest($quoteApprovalCreateRequestTransfer);

        if (!$quoteApprovalRequestValidationReponseTransfer->getIsSuccessful()) {
            return $this->createNotSuccessfulQuoteApprovalResponseTransfer();
        }

        $quoteTransfer = $quoteApprovalRequestValidationReponseTransfer->getQuote();

        $this->quoteLocker->lockQuote($quoteTransfer);
        $this->sharedCartFacade->deleteShareForQuote($quoteTransfer);
        $this->sharedCartFacade->createReadOnlyShareRelationForQuoteAndCompanyUser(
            $quoteTransfer->getIdQuote(),
            $quoteApprovalCreateRequestTransfer->getApproverCompanyUserId()
        );

        $quoteApprovalTransfer = $this->createQuoteApprovalTransfer(
            $quoteTransfer->getIdQuote(),
            $quoteApprovalCreateRequestTransfer->getApproverCompanyUserId()
        );

        return $this->createSuccessfullQuoteApprovalResponseTransfer($quoteApprovalTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    protected function createNotSuccessfulQuoteApprovalResponseTransfer(): QuoteApprovalResponseTransfer
    {
        $quoteApprovalResponseTransfer = new QuoteApprovalResponseTransfer();

        $quoteApprovalResponseTransfer->setIsSuccessful(false);
        $quoteApprovalResponseTransfer->setMessage(
            $this->createMessageTransfer(static::GLOSSARY_KEY_PERMISSION_FAILED)
        );

        return $quoteApprovalResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    protected function createSuccessfullQuoteApprovalResponseTransfer(QuoteApprovalTransfer $quoteApprovalTransfer): QuoteApprovalResponseTransfer
    {
        $approverCustomerTransfer = $quoteApprovalTransfer->getApprover()->getCustomer();

        $quoteApprovalResponseTransfer = new QuoteApprovalResponseTransfer();
        $quoteApprovalResponseTransfer->setIsSuccessful(true);
        $quoteApprovalResponseTransfer->setMessage(
            $this->createMessageTransfer(
                static::GLOSSARY_KEY_APPROVAL_CREATED,
                [
                    '%first_name%' => $approverCustomerTransfer->getFirstName(),
                    '%last_name%' => $approverCustomerTransfer->getLastName(),
                ]
            )
        );

        return $quoteApprovalResponseTransfer;
    }

    /**
     * @param string $message
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(string $message, array $parameters = []): MessageTransfer
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($message);
        $messageTransfer->setParameters($parameters);

        return $messageTransfer;
    }

    /**
     * @param int $idQuote
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer
     */
    protected function createQuoteApprovalTransfer(int $idQuote, int $idCompanyUser): QuoteApprovalTransfer
    {
        $quoteApprovalTransfer = new QuoteApprovalTransfer();

        $quoteApprovalTransfer->setStatus(QuoteApprovalConfig::STATUS_WAITING);
        $quoteApprovalTransfer->setFkCompanyUser($idCompanyUser);
        $quoteApprovalTransfer->setFkQuote($idQuote);

        return $this->quoteApprovalEntityManager
            ->saveQuoteApproval($quoteApprovalTransfer);
    }
}
