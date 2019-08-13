<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToMessengerFacadeInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface;

class QuoteItemOperation implements QuoteItemOperationInterface
{
    use PermissionAwareTrait;

    public const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface
     */
    protected $quoteResponseExpander;

    /**
     * @var \Spryker\Zed\PersistentCart\Business\Model\CartChangeRequestExpanderInterface
     */
    protected $cartChangeRequestExpander;

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\PersistentCart\Business\Model\CartChangeRequestExpanderInterface $cartChangeRequestExpander
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface $quoteResponseExpander
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        PersistentCartToCartFacadeInterface $cartFacade,
        PersistentCartToQuoteFacadeInterface $quoteFacade,
        CartChangeRequestExpanderInterface $cartChangeRequestExpander,
        QuoteResponseExpanderInterface $quoteResponseExpander,
        PersistentCartToMessengerFacadeInterface $messengerFacade
    ) {
        $this->cartFacade = $cartFacade;
        $this->quoteFacade = $quoteFacade;
        $this->quoteResponseExpander = $quoteResponseExpander;
        $this->cartChangeRequestExpander = $cartChangeRequestExpander;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransferList
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addItems(array $itemTransferList, QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer->setIsSuccessful(false);
        $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
        $quoteResponseTransfer->setCustomer($quoteTransfer->getCustomer());
        if (!$this->isQuoteWriteAllowed($quoteTransfer, $quoteTransfer->getCustomer())) {
            return $this->quoteResponseExpander->expand($quoteResponseTransfer);
        }

        $cartChangeTransfer = $this->createCartChangeTransfer($quoteTransfer);
        foreach ($itemTransferList as $itemTransfer) {
            $cartChangeTransfer->addItem($itemTransfer);
        }

        $quoteResponseTransfer = $this->cartFacade->addToCart($cartChangeTransfer);

        return $this->quoteResponseExpander->expand($this->quoteFacade->updateQuote($quoteResponseTransfer->getQuoteTransfer()));
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransferList
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addValidItems(array $itemTransferList, QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer->setIsSuccessful(false);
        $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
        $quoteResponseTransfer->setCustomer($quoteTransfer->getCustomer());
        if (!$this->isQuoteWriteAllowed($quoteTransfer, $quoteTransfer->getCustomer())) {
            return $this->quoteResponseExpander->expand($quoteResponseTransfer);
        }

        $cartChangeTransfer = $this->createCartChangeTransfer($quoteTransfer);
        foreach ($itemTransferList as $itemTransfer) {
            $cartChangeTransfer->addItem($itemTransfer);
        }
        $quoteTransfer = $this->cartFacade->addValid($cartChangeTransfer);

        return $this->quoteResponseExpander->expand($this->quoteFacade->updateQuote($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransferList
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeItems(array $itemTransferList, QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer->setIsSuccessful(false);
        $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
        $quoteResponseTransfer->setCustomer($quoteTransfer->getCustomer());
        if (!$this->isQuoteWriteAllowed($quoteTransfer, $quoteTransfer->getCustomer())) {
            return $this->quoteResponseExpander->expand($quoteResponseTransfer);
        }

        $cartChangeTransfer = $this->createCartChangeTransfer($quoteTransfer);
        foreach ($itemTransferList as $itemTransfer) {
            if (!empty($itemTransfer)) {
                $cartChangeTransfer->addItem($itemTransfer);
            }
        }
        $cartChangeTransfer = $this->cartChangeRequestExpander->removeItemRequestExpand($cartChangeTransfer);
        $quoteTransfer = $this->cartFacade->remove($cartChangeTransfer);
        $this->quoteFacade->updateQuote($quoteTransfer);

        $quoteResponseTransfer->setIsSuccessful(true);
        $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);

        return $this->quoteResponseExpander->expand($quoteResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function reloadItems(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        if (count($quoteTransfer->getItems())) {
            $quoteTransfer = $this->cartFacade->reloadItems($quoteTransfer);
        }
        $this->quoteFacade->updateQuote($quoteTransfer);

        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
        $quoteResponseTransfer->setCustomer($quoteTransfer->getCustomer());
        $quoteResponseTransfer->setIsSuccessful(true);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validate($quoteTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->cartFacade->validateQuote($quoteTransfer);
        $quoteResponseTransfer->setCustomer($quoteTransfer->getCustomer());
        $this->quoteFacade->updateQuote($quoteResponseTransfer->getQuoteTransfer());

        return $this->quoteResponseExpander->expand($quoteResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isQuoteWriteAllowed(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): bool
    {
        if ($customerTransfer->getCustomerReference() === $quoteTransfer->getCustomerReference()
            || ($customerTransfer->getCompanyUserTransfer()
                && $this->can('WriteSharedCartPermissionPlugin', $customerTransfer->getCompanyUserTransfer()->getIdCompanyUser(), $quoteTransfer->getIdQuote())
            )
        ) {
            return true;
        }
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue(static::GLOSSARY_KEY_PERMISSION_FAILED);
        $this->messengerFacade->addErrorMessage($messageTransfer);

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer(QuoteTransfer $quoteTransfer): CartChangeTransfer
    {
        $items = $quoteTransfer->getItems();

        if (count($items) === 0) {
            $quoteTransfer->setItems(new ArrayObject());
        }

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setQuote($quoteTransfer);

        return $cartChangeTransfer;
    }
}
