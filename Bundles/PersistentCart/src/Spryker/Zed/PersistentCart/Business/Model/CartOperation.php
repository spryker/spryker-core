<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToMessengerFacadeInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface;
use Spryker\Zed\PersistentCart\Dependency\Plugin\QuoteItemFinderPluginInterface;

class CartOperation implements CartOperationInterface
{
    use PermissionAwareTrait;

    public const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';
    public const GLOSSARY_KEY_QUOTE_NOT_AVAILABLE = 'persistent_cart.error.quote.not_available';

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
     * @var \Spryker\Zed\PersistentCart\Dependency\Plugin\QuoteItemFinderPluginInterface
     */
    protected $itemFinderPlugin;

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
     * @param \Spryker\Zed\PersistentCart\Dependency\Plugin\QuoteItemFinderPluginInterface $itemFinderPlugin
     * @param \Spryker\Zed\PersistentCart\Business\Model\CartChangeRequestExpanderInterface $cartChangeRequestExpander
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface $quoteResponseExpander
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        PersistentCartToCartFacadeInterface $cartFacade,
        PersistentCartToQuoteFacadeInterface $quoteFacade,
        QuoteItemFinderPluginInterface $itemFinderPlugin,
        CartChangeRequestExpanderInterface $cartChangeRequestExpander,
        QuoteResponseExpanderInterface $quoteResponseExpander,
        PersistentCartToMessengerFacadeInterface $messengerFacade
    ) {
        $this->cartFacade = $cartFacade;
        $this->quoteFacade = $quoteFacade;
        $this->quoteResponseExpander = $quoteResponseExpander;
        $this->itemFinderPlugin = $itemFinderPlugin;
        $this->cartChangeRequestExpander = $cartChangeRequestExpander;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function add(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        $persistentCartChangeTransfer->requireCustomer();
        $quoteTransfer = $this->findCustomerQuoteById(
            $persistentCartChangeTransfer->getIdQuote(),
            $persistentCartChangeTransfer->getCustomer()
        );
        if (!$quoteTransfer) {
            return $this->createQuoteNotFoundResult($persistentCartChangeTransfer->getCustomer());
        }

        return $this->addItems((array)$persistentCartChangeTransfer->getItems(), $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function remove(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        $persistentCartChangeTransfer->requireCustomer();
        $quoteTransfer = $this->findCustomerQuoteById(
            $persistentCartChangeTransfer->getIdQuote(),
            $persistentCartChangeTransfer->getCustomer()
        );
        if (!$quoteTransfer) {
            return $this->createQuoteNotFoundResult($persistentCartChangeTransfer->getCustomer());
        }
        $itemTransferList = [];
        foreach ($persistentCartChangeTransfer->getItems() as $itemTransfer) {
            $itemTransferList[] = $this->findItemInQuote($itemTransfer, $quoteTransfer);
        }

        return $this->removeItems($itemTransferList, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function reloadItems(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
        $quoteResponseTransfer->setIsSuccessful(false);
        $quoteTransfer->requireCustomer();
        $customerQuoteTransfer = $this->findCustomerQuoteById(
            $quoteTransfer->getIdQuote(),
            $quoteTransfer->getCustomer()
        );
        if (!$customerQuoteTransfer) {
            return $this->createQuoteNotFoundResult($quoteTransfer->getCustomer());
        }
        $quoteTransfer->fromArray($customerQuoteTransfer->modifiedToArray(), true);
        $quoteTransfer = $this->cartFacade->reloadItems($quoteTransfer);
        $this->quoteFacade->updateQuote($quoteTransfer);

        $quoteResponseTransfer->setIsSuccessful(true);
        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function changeItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer
    {
        $persistentCartChangeQuantityTransfer->requireCustomer();
        $itemTransfer = $persistentCartChangeQuantityTransfer->getItem();
        $quoteTransfer = $this->findCustomerQuoteById(
            $persistentCartChangeQuantityTransfer->getIdQuote(),
            $persistentCartChangeQuantityTransfer->getCustomer()
        );
        if (!$quoteTransfer) {
            return $this->createQuoteNotFoundResult($persistentCartChangeQuantityTransfer->getCustomer());
        }
        $quoteItemTransfer = $this->findItemInQuote($itemTransfer, $quoteTransfer);
        if (!$quoteItemTransfer) {
            $quoteResponseTransfer = new QuoteResponseTransfer();
            $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
            $quoteResponseTransfer->setIsSuccessful(false);
            return $quoteResponseTransfer;
        }
        if ($itemTransfer->getQuantity() === 0) {
            return $this->removeItems([$quoteItemTransfer], $quoteTransfer);
        }

        $delta = abs($quoteItemTransfer->getQuantity() - $itemTransfer->getQuantity());
        if ($delta === 0) {
            $quoteResponseTransfer = new QuoteResponseTransfer();
            $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
            $quoteResponseTransfer->setIsSuccessful(false);
            return $quoteResponseTransfer;
        }

        $changeItemTransfer = clone $quoteItemTransfer;
        $changeItemTransfer->setQuantity($delta);
        if ($quoteItemTransfer->getQuantity() > $itemTransfer->getQuantity()) {
            return $this->removeItems([$changeItemTransfer], $quoteTransfer);
        }

        return $this->addItems([$changeItemTransfer], $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function decreaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer
    {
        $persistentCartChangeQuantityTransfer->requireCustomer();
        $quoteTransfer = $this->findCustomerQuoteById(
            $persistentCartChangeQuantityTransfer->getIdQuote(),
            $persistentCartChangeQuantityTransfer->getCustomer()
        );
        $decreaseItemTransfer = $this->findItemInQuote($persistentCartChangeQuantityTransfer->getItem(), $quoteTransfer);
        if (!$decreaseItemTransfer || !$persistentCartChangeQuantityTransfer->getItem()->getQuantity()) {
            return $quoteTransfer;
        }

        $itemTransfer = clone $decreaseItemTransfer;
        $itemTransfer->setQuantity(
            $persistentCartChangeQuantityTransfer->getItem()->getQuantity()
        );

        return $this->removeItems([$itemTransfer], $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function increaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer
    {
        $persistentCartChangeQuantityTransfer->requireCustomer();
        $quoteTransfer = $this->findCustomerQuoteById(
            $persistentCartChangeQuantityTransfer->getIdQuote(),
            $persistentCartChangeQuantityTransfer->getCustomer()
        );
        $decreaseItemTransfer = $this->findItemInQuote($persistentCartChangeQuantityTransfer->getItem(), $quoteTransfer);
        if (!$decreaseItemTransfer || !$persistentCartChangeQuantityTransfer->getItem()->getQuantity()) {
            $quoteResponseTransfer = new QuoteResponseTransfer();
            $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
            $quoteResponseTransfer->setIsSuccessful(false);
            return $quoteResponseTransfer;
        }

        $itemTransfer = clone $decreaseItemTransfer;
        $itemTransfer->setQuantity(
            $persistentCartChangeQuantityTransfer->getItem()->getQuantity()
        );

        return $this->addItems([$itemTransfer], $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validate($quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->requireCustomer();
        $customerQuoteTransfer = $this->findCustomerQuoteById(
            $quoteTransfer->getIdQuote(),
            $quoteTransfer->getCustomer()
        );
        if (!$customerQuoteTransfer) {
            return $this->createQuoteNotFoundResult($quoteTransfer->getCustomer());
        }
        if ($customerQuoteTransfer) {
            $quoteTransfer->fromArray($customerQuoteTransfer->modifiedToArray(), true);
        }
        $quoteResponseTransfer = $this->cartFacade->validateQuote($quoteTransfer);
        $this->quoteFacade->updateQuote($quoteResponseTransfer->getQuoteTransfer());

        return $this->quoteResponseExpander->expand($quoteResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransferList
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function addItems(array $itemTransferList, QuoteTransfer $quoteTransfer): QuoteResponseTransfer
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

        $quoteTransfer = $this->cartFacade->add($cartChangeTransfer);
        $this->quoteFacade->updateQuote($quoteTransfer);
        $quoteResponseTransfer->setIsSuccessful(true);
        $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);

        return $this->quoteResponseExpander->expand($quoteResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransferList
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function removeItems(array $itemTransferList, QuoteTransfer $quoteTransfer): QuoteResponseTransfer
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
        $cartChangeTransfer = $this->cartChangeRequestExpander->removeItemRequestExpand($cartChangeTransfer);
        $quoteTransfer = $this->cartFacade->remove($cartChangeTransfer);
        $this->quoteFacade->updateQuote($quoteTransfer);

        $quoteResponseTransfer->setIsSuccessful(true);
        $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);

        return $this->quoteResponseExpander->expand($quoteResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return null|\Generated\Shared\Transfer\ItemTransfer
     */
    protected function findItemInQuote(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): ?ItemTransfer
    {
        return $this->itemFinderPlugin->findItem($quoteTransfer, $itemTransfer->getSku(), $itemTransfer->getGroupKey());
    }

    /**
     * @param int $idQuote
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return null|\Generated\Shared\Transfer\QuoteTransfer
     */
    protected function findCustomerQuoteById($idQuote, CustomerTransfer $customerTransfer): ?QuoteTransfer
    {
        if (!$idQuote) {
            $quoteTransfer = new QuoteTransfer();
            $quoteTransfer->setCustomer($customerTransfer);

            return $quoteTransfer;
        }
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isQuoteReadAllowed(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): bool
    {
        if (strcmp($customerTransfer->getCustomerReference(), $quoteTransfer->getCustomerReference()) === 0
            || ($customerTransfer->getCompanyUserTransfer()
                && $this->can('ReadSharedCartPermissionPlugin', $customerTransfer->getCompanyUserTransfer()->getIdCompanyUser(), $quoteTransfer->getIdQuote())
            )
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isQuoteWriteAllowed(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): bool
    {
        if (strcmp($customerTransfer->getCustomerReference(), $quoteTransfer->getCustomerReference()) === 0
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

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createQuoteNotFoundResult(CustomerTransfer $customerTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer->setCustomer($customerTransfer);
        $quoteResponseTransfer->setQuoteTransfer($this->resolveCustomerQuote($customerTransfer));
        $quoteResponseTransfer->setIsSuccessful(false);
        
        return $this->quoteResponseExpander->expand($quoteResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function resolveCustomerQuote(CustomerTransfer $customerTransfer): QuoteTransfer
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
}
