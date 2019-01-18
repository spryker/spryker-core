<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApproval;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToSharedCartFacadeInterface;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface;

class QuoteApprovalCreator implements QuoteApprovalCreatorInterface
{
    use TransactionTrait;

    protected const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';
    protected const GLOSSARY_KEY_APPROVAL_CREATED = 'quote_approval.created';

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface
     */
    protected $cartFacade;

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
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToSharedCartFacadeInterface $sharedCartFacade
     * @param \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalRequestValidatorInterface $quoteApprovalRequestValidator
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager
     */
    public function __construct(
        QuoteApprovalToQuoteFacadeInterface $quoteFacade,
        QuoteApprovalToCartFacadeInterface $cartFacade,
        QuoteApprovalToSharedCartFacadeInterface $sharedCartFacade,
        QuoteApprovalRequestValidatorInterface $quoteApprovalRequestValidator,
        QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->cartFacade = $cartFacade;
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
        if (!$this->quoteApprovalRequestValidator->isCreateQuoteApprovalRequestValid($quoteApprovalCreateRequestTransfer)) {
            return $this->createNotSuccessfulQuoteApprovalResponseTransfer();
        }

        $quoteTransfer = $this->findQuoteByIdQuote($quoteApprovalCreateRequestTransfer->getIdQuote());

        $quoteTransfer = $this->cartFacade->lockQuote($quoteTransfer);
        $this->quoteFacade->updateQuote($quoteTransfer);

        $this->sharedCartFacade->deleteShareForQuote($quoteTransfer);
        $this->sharedCartFacade->createReadOnlyShareRelationForQuoteAndCompanyUser(
            $quoteTransfer->getIdQuote(),
            $quoteApprovalCreateRequestTransfer->getIdCompanyUser()
        );

        $quoteApprovalTransfer = $this->createQuoteApprovalTransfer(
            $quoteTransfer->getIdQuote(),
            $quoteApprovalCreateRequestTransfer->getIdCompanyUser()
        );

        return $this->createSuccessfullQuoteApprovalResponseTransfer($quoteApprovalTransfer);
    }

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function findQuoteByIdQuote(int $idQuote): QuoteTransfer
    {
        $quoteTransfer = $this->quoteFacade->findQuoteById(
            $idQuote
        )->getQuoteTransfer();

        $quoteTransfer->setCustomer(
            (new CustomerTransfer())->setCustomerReference($quoteTransfer->getCustomerReference())
        );

        return $quoteTransfer;
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
