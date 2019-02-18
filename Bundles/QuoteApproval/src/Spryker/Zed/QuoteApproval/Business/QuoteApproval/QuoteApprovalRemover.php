<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApproval;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\QuoteApproval\Business\Quote\QuoteLockerInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToSharedCartFacadeInterface;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface;

class QuoteApprovalRemover implements QuoteApprovalRemoverInterface
{
    use TransactionTrait;

    protected const GLOSSARY_KEY_APPROVAL_REMOVED = 'quote_approval.removed';

    /**
     * @var \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalRequestValidatorInterface
     */
    protected $quoteApprovalRequestValidator;

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToSharedCartFacadeInterface
     */
    protected $sharedCartFacade;

    /**
     * @var \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface
     */
    protected $quoteApprovalEntityManager;

    /**
     * @var \Spryker\Zed\QuoteApproval\Business\Quote\QuoteLockerInterface
     */
    protected $quoteLocker;

    /**
     * @param \Spryker\Zed\QuoteApproval\Business\Quote\QuoteLockerInterface $quoteLocker
     * @param \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalRequestValidatorInterface $quoteApprovalRequestValidator
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToSharedCartFacadeInterface $sharedCartFacade
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager
     */
    public function __construct(
        QuoteLockerInterface $quoteLocker,
        QuoteApprovalRequestValidatorInterface $quoteApprovalRequestValidator,
        QuoteApprovalToSharedCartFacadeInterface $sharedCartFacade,
        QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager
    ) {
        $this->quoteLocker = $quoteLocker;
        $this->quoteApprovalRequestValidator = $quoteApprovalRequestValidator;
        $this->sharedCartFacade = $sharedCartFacade;
        $this->quoteApprovalEntityManager = $quoteApprovalEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function removeQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteApprovalRequestTransfer) {
            return $this->executeRemoveQuoteApprovalTransaction($quoteApprovalRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    protected function executeRemoveQuoteApprovalTransaction(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        $quoteApprovalResponseTransfer = new QuoteApprovalResponseTransfer();

        $quoteApprovalResponse = $this->quoteApprovalRequestValidator
            ->validateQuoteApprovalRemoveRequest($quoteApprovalRequestTransfer);

        if (!$quoteApprovalResponse->getIsSuccessful()) {
            return $quoteApprovalResponse;
        }

        $this->executeQuoteApprovalRemoval($quoteApprovalResponse, $quoteApprovalRequestTransfer);

        return $quoteApprovalResponseTransfer->setIsSuccessful(true)
            ->addMessage($this->createMessageTransfer(static::GLOSSARY_KEY_APPROVAL_REMOVED));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalResponseTransfer $quoteApprovalResponse
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return void
     */
    protected function executeQuoteApprovalRemoval(
        QuoteApprovalResponseTransfer $quoteApprovalResponse,
        QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
    ): void {
        $quoteTransfer = $quoteApprovalResponse->getQuote();

        $this->quoteLocker->unlockQuote($quoteTransfer);

        $this->sharedCartFacade->deleteShareForQuote($quoteTransfer);
        $this->quoteApprovalEntityManager->deleteQuoteApprovalById(
            $quoteApprovalRequestTransfer->getIdQuoteApproval()
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
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($message);
        $messageTransfer->setParameters($parameters);

        return $messageTransfer;
    }
}
