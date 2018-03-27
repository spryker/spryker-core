<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Business\Activator;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteActivationRequestTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToMessengerFacadeInterface;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToPersistentCartFacadeInterface;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface;

class QuoteActivator implements QuoteActivatorInterface
{
    use TransactionTrait;

    public const MULTI_CART_SET_DEFAULT_SUCCESS = 'multi_cart.cart.set_default.success';

    /**
     * @var \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToPersistentCartFacadeInterface
     */
    protected $persistentCartFacade;

    /**
     * @var \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToPersistentCartFacadeInterface $persistentCartFacade
     * @param \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        MultiCartToQuoteFacadeInterface $quoteFacade,
        MultiCartToPersistentCartFacadeInterface $persistentCartFacade,
        MultiCartToMessengerFacadeInterface $messengerFacade
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->persistentCartFacade = $persistentCartFacade;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteActivationRequestTransfer $quoteActivationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setDefaultQuote(QuoteActivationRequestTransfer $quoteActivationRequestTransfer): QuoteResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteActivationRequestTransfer) {
            return $this->executeSetDefaultQuoteTransaction($quoteActivationRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteActivationRequestTransfer $quoteActivationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function executeSetDefaultQuoteTransaction(QuoteActivationRequestTransfer $quoteActivationRequestTransfer): QuoteResponseTransfer
    {
        $customerQuotesCollectionTransfer = $this->findCustomerQuotes($quoteActivationRequestTransfer->getCustomer());
        $quoteToActivateTransfer = $this->findQuoteById($quoteActivationRequestTransfer->getIdQuote(), $customerQuotesCollectionTransfer);

        $quoteResponseTransfer = new QuoteResponseTransfer();
        if (!$quoteToActivateTransfer) {
            $quoteResponseTransfer->setIsSuccessful(false);

            return $quoteResponseTransfer;
        }

        if ($quoteToActivateTransfer->getIsDefault()) {
            $quoteResponseTransfer
                ->setIsSuccessful(true)
                ->setQuoteTransfer($quoteToActivateTransfer);

            return $quoteResponseTransfer;
        }

        $this->resetQuoteDefaultFlag($customerQuotesCollectionTransfer);

        $quoteToActivateTransfer
            ->setCustomer($quoteActivationRequestTransfer->getCustomer())
            ->setIsDefault(true);

        $this->addSuccessMessage($quoteToActivateTransfer->getName());

        return $this->quoteFacade->persistQuote($quoteToActivateTransfer);
    }

    /**
     * @param int $idQuote
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return null|\Generated\Shared\Transfer\QuoteTransfer
     */
    protected function findQuoteById($idQuote, QuoteCollectionTransfer $quoteCollectionTransfer): ?QuoteTransfer
    {
        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if ($quoteTransfer->getIdQuote() === $idQuote) {
                return $quoteTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function findCustomerQuotes(CustomerTransfer $customerTransfer): QuoteCollectionTransfer
    {
        $quoteCriteriaFilterTransfer = new QuoteCriteriaFilterTransfer();
        $quoteCriteriaFilterTransfer->setCustomerReference($customerTransfer->getCustomerReference());

        $customerQuoteCollectionTransfer = $this->quoteFacade->getQuoteCollection($quoteCriteriaFilterTransfer);
        foreach ($customerQuoteCollectionTransfer->getQuotes() as $customerQuoteTransfer) {
            $customerQuoteTransfer->setCustomer($customerTransfer);
        }

        return $customerQuoteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quotesCollectionTransfer
     *
     * @return void
     */
    protected function resetQuoteDefaultFlag(QuoteCollectionTransfer $quotesCollectionTransfer): void
    {
        foreach ($quotesCollectionTransfer->getQuotes() as $quoteTransfer) {
            if (!$quoteTransfer->getIsDefault()) {
                continue;
            }

            $quoteTransfer->setIsDefault(false);
            $this->quoteFacade->persistQuote($quoteTransfer);
        }
    }

    /**
     * @param string $quoteName
     *
     * @return void
     */
    protected function addSuccessMessage($quoteName): void
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer
            ->setValue(static::MULTI_CART_SET_DEFAULT_SUCCESS)
            ->setParameters(['%quote%' => $quoteName]);

        $this->messengerFacade->addInfoMessage($messageTransfer);
    }
}
