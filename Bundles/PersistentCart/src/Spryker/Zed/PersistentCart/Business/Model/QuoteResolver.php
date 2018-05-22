<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToMessengerFacadeInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface;

class QuoteResolver implements QuoteResolverInterface
{
    use PermissionAwareTrait;
    public const GLOSSARY_KEY_QUOTE_NOT_AVAILABLE = 'persistent_cart.error.quote.not_available';

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface
     */
    protected $quoteResponseExpander;

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface $quoteResponseExpander
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        PersistentCartToQuoteFacadeInterface $quoteFacade,
        QuoteResponseExpanderInterface $quoteResponseExpander,
        PersistentCartToMessengerFacadeInterface $messengerFacade
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->quoteResponseExpander = $quoteResponseExpander;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param int $idQuote
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer|null $quoteUpdateRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function resolveCustomerQuote(
        int $idQuote,
        CustomerTransfer $customerTransfer,
        ?QuoteUpdateRequestAttributesTransfer $quoteUpdateRequestAttributesTransfer = null
    ): QuoteResponseTransfer {
        if (!$idQuote) {
            return $this->createNewQuote($customerTransfer, $quoteUpdateRequestAttributesTransfer);
        }
        $customerQuoteTransfer = $this->findCustomerQuoteById(
            $idQuote,
            $customerTransfer
        );

        if (!$customerQuoteTransfer) {
            return $this->createQuoteNotFoundResult($customerTransfer);
        }

        return $this->updateQuote($customerTransfer, $customerQuoteTransfer, $quoteUpdateRequestAttributesTransfer);
    }

    /**
     * @param int $idQuote
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return null|\Generated\Shared\Transfer\QuoteTransfer
     */
    protected function findCustomerQuoteById(int $idQuote, CustomerTransfer $customerTransfer): ?QuoteTransfer
    {
        $quoteResponseTransfer = $this->quoteFacade->findQuoteById($idQuote);
        if (!$quoteResponseTransfer->getIsSuccessful() || !$this->isQuoteReadAllowed($quoteResponseTransfer->getQuoteTransfer(), $customerTransfer)
        ) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setValue(static::GLOSSARY_KEY_QUOTE_NOT_AVAILABLE);
            $this->messengerFacade->addErrorMessage($messageTransfer);

            return null;
        }
        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $quoteTransfer->setCustomer($customerTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createQuoteNotFoundResult(CustomerTransfer $customerTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer->setCustomer($customerTransfer);
        $quoteResponseTransfer->setQuoteTransfer($this->resolveDefaultCustomerQuote($customerTransfer));
        $quoteResponseTransfer->setIsSuccessful(false);

        return $this->quoteResponseExpander->expand($quoteResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function resolveDefaultCustomerQuote(CustomerTransfer $customerTransfer): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();
        $customerQuoteTransfer = $this->quoteFacade->findQuoteByCustomer($customerTransfer);
        if ($customerQuoteTransfer->getIsSuccessful()) {
            $quoteTransfer = $customerQuoteTransfer->getQuoteTransfer();
        }
        $quoteTransfer->setCustomer($customerTransfer);
        if (!$quoteTransfer->getIdQuote()) {
            $this->quoteFacade->createQuote($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer|null $quoteUpdateRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createNewQuote(CustomerTransfer $customerTransfer, ?QuoteUpdateRequestAttributesTransfer $quoteUpdateRequestAttributesTransfer = null): QuoteResponseTransfer
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setCustomer($customerTransfer);
        $quoteTransfer->setCustomerReference($customerTransfer->getCustomerReference());
        if ($quoteUpdateRequestAttributesTransfer) {
            $quoteTransfer->fromArray($quoteUpdateRequestAttributesTransfer->modifiedToArray(), true);
        }

        return $this->quoteFacade->createQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isQuoteReadAllowed(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): bool
    {
        return strcmp($customerTransfer->getCustomerReference(), $quoteTransfer->getCustomerReference()) === 0
            || ($customerTransfer->getCompanyUserTransfer()
                && $this->can('ReadSharedCartPermissionPlugin', $customerTransfer->getCompanyUserTransfer()->getIdCompanyUser(), $quoteTransfer->getIdQuote())
            );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param null|\Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer $quoteUpdateRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function updateQuote(
        CustomerTransfer $customerTransfer,
        QuoteTransfer $quoteTransfer,
        ?QuoteUpdateRequestAttributesTransfer $quoteUpdateRequestAttributesTransfer = null
    ): QuoteResponseTransfer {
        if ($quoteUpdateRequestAttributesTransfer) {
            $quoteTransfer->fromArray($quoteUpdateRequestAttributesTransfer->modifiedToArray(), true);
        }

        $quoteTransfer->setCustomer($customerTransfer);
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer->setIsSuccessful(true);
        $quoteResponseTransfer->setCustomer($customerTransfer);
        $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);

        return $quoteResponseTransfer;
    }
}
