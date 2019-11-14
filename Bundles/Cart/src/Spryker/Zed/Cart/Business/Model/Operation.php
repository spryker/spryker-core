<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface;
use Spryker\Zed\Cart\CartConfig;
use Spryker\Zed\Cart\Dependency\Facade\CartToCalculationInterface;
use Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface;
use Spryker\Zed\Cart\Dependency\Facade\CartToQuoteFacadeInterface;
use Spryker\Zed\CartExtension\Dependency\Plugin\TerminationAwareCartPreCheckPluginInterface;

class Operation implements OperationInterface
{
    public const ADD_ITEMS_SUCCESS = 'cart.add.items.success';
    public const REMOVE_ITEMS_SUCCESS = 'cart.remove.items.success';

    protected const TERMINATION_EVENT_NAME_ADD = 'add';
    protected const TERMINATION_EVENT_NAME_REMOVE = 'remove';
    protected const TERMINATION_EVENT_NAME_RELOAD = 'reload';
    protected const GLOSSARY_KEY_LOCKED_CART_CHANGE_DENIED = 'cart.locked.change_denied';

    protected const MESSAGE_TYPE_NOTIFICATION = 'notification';

    /**
     * @var \Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface
     */
    protected $cartStorageProvider;

    /**
     * @var \Spryker\Zed\Cart\Dependency\Facade\CartToCalculationInterface
     */
    protected $calculationFacade;

    /**
     * @var \Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @var \Spryker\Zed\Cart\Dependency\Facade\CartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface[]
     */
    protected $itemExpanderPlugins = [];

    /**
     * @var \Spryker\Zed\CartExtension\Dependency\Plugin\CartChangeTransferNormalizerPluginInterface[]
     */
    protected $cartBeforePreCheckNormalizerPlugins = [];

    /**
     * @var \Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface[]
     */
    protected $preCheckPlugins;

    /**
     * @var \Spryker\Zed\CartExtension\Dependency\Plugin\CartRemovalPreCheckPluginInterface[]
     */
    protected $cartRemovalPreCheckPlugins;

    /**
     * @var \Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationPostSavePluginInterface[]
     */
    protected $postSavePlugins = [];

    /**
     * @var \Spryker\Zed\CartExtension\Dependency\Plugin\PreReloadItemsPluginInterface[]
     */
    protected $preReloadPlugins = [];

    /**
     * @var \Spryker\Zed\CartExtension\Dependency\Plugin\CartTerminationPluginInterface[]
     */
    protected $terminationPlugins = [];

    /**
     * @var array|\Spryker\Zed\CartExtension\Dependency\Plugin\PostReloadItemsPluginInterface[]
     */
    protected $postReloadItemsPlugins = [];

    /**
     * @param \Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface $cartStorageProvider
     * @param \Spryker\Zed\Cart\Dependency\Facade\CartToCalculationInterface $calculationFacade
     * @param \Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface $messengerFacade
     * @param \Spryker\Zed\Cart\Dependency\Facade\CartToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface[] $itemExpanderPlugins
     * @param \Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface[] $preCheckPlugins
     * @param \Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationPostSavePluginInterface[] $postSavePlugins
     * @param \Spryker\Zed\CartExtension\Dependency\Plugin\CartTerminationPluginInterface[] $terminationPlugins
     * @param \Spryker\Zed\CartExtension\Dependency\Plugin\CartRemovalPreCheckPluginInterface[] $cartRemovalPreCheckPlugins
     * @param \Spryker\Zed\CartExtension\Dependency\Plugin\PostReloadItemsPluginInterface[] $postReloadItemsPlugins
     * @param \Spryker\Zed\CartExtension\Dependency\Plugin\CartChangeTransferNormalizerPluginInterface[] $cartBeforePreCheckNormalizerPlugins
     */
    public function __construct(
        StorageProviderInterface $cartStorageProvider,
        CartToCalculationInterface $calculationFacade,
        CartToMessengerInterface $messengerFacade,
        CartToQuoteFacadeInterface $quoteFacade,
        array $itemExpanderPlugins,
        array $preCheckPlugins,
        array $postSavePlugins,
        array $terminationPlugins,
        array $cartRemovalPreCheckPlugins,
        array $postReloadItemsPlugins,
        array $cartBeforePreCheckNormalizerPlugins
    ) {
        $this->cartStorageProvider = $cartStorageProvider;
        $this->calculationFacade = $calculationFacade;
        $this->messengerFacade = $messengerFacade;
        $this->quoteFacade = $quoteFacade;
        $this->itemExpanderPlugins = $itemExpanderPlugins;
        $this->preCheckPlugins = $preCheckPlugins;
        $this->postSavePlugins = $postSavePlugins;
        $this->terminationPlugins = $terminationPlugins;
        $this->cartRemovalPreCheckPlugins = $cartRemovalPreCheckPlugins;
        $this->postReloadItemsPlugins = $postReloadItemsPlugins;
        $this->cartBeforePreCheckNormalizerPlugins = $cartBeforePreCheckNormalizerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addValid(CartChangeTransfer $cartChangeTransfer): QuoteTransfer
    {
        $cartChangeTransfer->requireQuote();

        $quoteTransfer = $cartChangeTransfer->getQuote();

        if ($this->quoteFacade->isQuoteLocked($quoteTransfer)) {
            $this->messengerFacade->addErrorMessage($this->createMessengerMessageTransfer(static::GLOSSARY_KEY_LOCKED_CART_CHANGE_DENIED));

            return $quoteTransfer;
        }

        $itemsTransfer = $cartChangeTransfer->getItems();

        foreach ($itemsTransfer as $currentItemTransfer) {
            $itemsCollection = new ArrayObject([$currentItemTransfer]);
            $currentCartChangeTransfer = new CartChangeTransfer();
            $currentCartChangeTransfer->setQuote($quoteTransfer);
            $currentCartChangeTransfer->setItems($itemsCollection);
            $currentCartChangeTransfer->setOperation(CartConfig::OPERATION_ADD);

            $quoteTransfer = $this->addToCart($currentCartChangeTransfer)->getQuoteTransfer();
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function add(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->addToCart($cartChangeTransfer)->getQuoteTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addToCart(CartChangeTransfer $cartChangeTransfer): QuoteResponseTransfer
    {
        $cartChangeTransfer->requireQuote();
        $cartChangeTransfer->setOperation(CartConfig::OPERATION_ADD);

        $originalQuoteTransfer = (new QuoteTransfer())
            ->fromArray($cartChangeTransfer->getQuote()->modifiedToArray(), true);

        $quoteResponseTransfer = (new QuoteResponseTransfer())
            ->setIsSuccessful(false)
            ->setQuoteTransfer($originalQuoteTransfer);

        if ($this->quoteFacade->isQuoteLocked($originalQuoteTransfer)) {
            $this->messengerFacade->addErrorMessage($this->createMessengerMessageTransfer(static::GLOSSARY_KEY_LOCKED_CART_CHANGE_DENIED));

            return $this->addQuoteErrorsToQuoteResponse($quoteResponseTransfer);
        }

        $cartChangeTransfer = $this->normalizeCartChangeTransfer($cartChangeTransfer);
        $this->addInfoMessages(
            $this->getNotificationMessages($cartChangeTransfer)
        );

        if (!$this->preCheckCart($cartChangeTransfer)) {
            return $this->addQuoteErrorsToQuoteResponse($quoteResponseTransfer);
        }

        $expandedCartChangeTransfer = $this->expandChangedItems($cartChangeTransfer);
        $quoteTransfer = $this->cartStorageProvider->addItems($expandedCartChangeTransfer);
        $quoteTransfer = $this->executePostSavePlugins($quoteTransfer);
        $quoteTransfer = $this->recalculate($quoteTransfer);

        if ($this->isTerminated(static::TERMINATION_EVENT_NAME_ADD, $cartChangeTransfer, $quoteTransfer)) {
            return $quoteResponseTransfer;
        }

        $this->messengerFacade->addSuccessMessage($this->createMessengerMessageTransfer(self::ADD_ITEMS_SUCCESS));

        return $quoteResponseTransfer
            ->setQuoteTransfer($quoteTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function remove(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->removeFromCart($cartChangeTransfer)->getQuoteTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeFromCart(CartChangeTransfer $cartChangeTransfer): QuoteResponseTransfer
    {
        $cartChangeTransfer->requireQuote();
        $cartChangeTransfer->setOperation(CartConfig::OPERATION_REMOVE);

        $originalQuoteTransfer = (new QuoteTransfer())
            ->fromArray($cartChangeTransfer->getQuote()->modifiedToArray(), true);

        $quoteResponseTransfer = (new QuoteResponseTransfer())
            ->setQuoteTransfer($originalQuoteTransfer)
            ->setIsSuccessful(false);

        if (!$this->executeCartRemovalPreCheckPlugins($cartChangeTransfer)) {
            return $this->addQuoteErrorsToQuoteResponse($quoteResponseTransfer);
        }

        $expandedCartChangeTransfer = $this->expandChangedItems($cartChangeTransfer);
        $quoteTransfer = $this->cartStorageProvider->removeItems($expandedCartChangeTransfer);
        $quoteTransfer = $this->executePostSavePlugins($quoteTransfer);
        $quoteTransfer = $this->recalculate($quoteTransfer);

        if ($this->isTerminated(static::TERMINATION_EVENT_NAME_REMOVE, $cartChangeTransfer, $quoteTransfer)) {
            return $quoteResponseTransfer;
        }

        $this->messengerFacade->addSuccessMessage($this->createMessengerMessageTransfer(self::REMOVE_ITEMS_SUCCESS));

        return $quoteResponseTransfer
            ->setQuoteTransfer($quoteTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function reloadItems(QuoteTransfer $quoteTransfer)
    {
        if ($this->quoteFacade->isQuoteLocked($quoteTransfer)) {
            return $quoteTransfer;
        }

        $quoteValidationResponseTransfer = $this->quoteFacade->validateQuote($quoteTransfer);
        if (!$quoteValidationResponseTransfer->getIsSuccessful()) {
            return $quoteTransfer;
        }

        $originalQuoteTransfer = (new QuoteTransfer())->fromArray($quoteTransfer->modifiedToArray(), true);

        $quoteTransfer = $this->executePreReloadPlugins($quoteTransfer);

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setItems($quoteTransfer->getItems());

        $quoteTransfer->setItems(new ArrayObject());

        $cartChangeTransfer->setQuote($quoteTransfer);

        if (!$this->preCheckCart($cartChangeTransfer)) {
            return $originalQuoteTransfer;
        }

        $expandedCartChangeTransfer = $this->expandChangedItems($cartChangeTransfer);
        $quoteTransfer = $this->cartStorageProvider->addItems($expandedCartChangeTransfer);
        $quoteTransfer = $this->executePostSavePlugins($quoteTransfer);
        $quoteTransfer = $this->recalculate($quoteTransfer);

        $quoteTransfer = $this->executePostReloadItemsPlugins($quoteTransfer);

        if ($this->isTerminated(static::TERMINATION_EVENT_NAME_RELOAD, $cartChangeTransfer, $quoteTransfer)) {
            return $originalQuoteTransfer;
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function addQuoteErrorsToQuoteResponse(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        $errorMessages = $this->messengerFacade->getStoredMessages()->getErrorMessages();
        if (!count($errorMessages)) {
            return $quoteResponseTransfer;
        }

        foreach ($errorMessages as $errorMessage) {
            $quoteResponseTransfer->addError((new QuoteErrorTransfer())->setMessage($errorMessage));
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executePostReloadItemsPlugins(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($this->postReloadItemsPlugins as $postReloadItemPlugin) {
            $quoteTransfer = $postReloadItemPlugin->postReloadItems($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param string $terminationEventName
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $calculatedQuoteTransfer
     *
     * @return bool
     */
    protected function isTerminated(string $terminationEventName, CartChangeTransfer $cartChangeTransfer, QuoteTransfer $calculatedQuoteTransfer)
    {
        foreach ($this->terminationPlugins as $terminationPlugin) {
            if ($terminationPlugin->isTerminated($terminationEventName, $cartChangeTransfer, $calculatedQuoteTransfer)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function normalizeCartChangeTransfer(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($this->cartBeforePreCheckNormalizerPlugins as $cartItemNormalizerPlugin) {
            if (!$cartItemNormalizerPlugin->isApplicable($cartChangeTransfer)) {
                continue;
            }
            $cartChangeTransfer = $cartItemNormalizerPlugin->normalizeCartChangeTransfer($cartChangeTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return bool
     */
    protected function preCheckCart(CartChangeTransfer $cartChangeTransfer)
    {
        $isCartValid = true;
        foreach ($this->preCheckPlugins as $preCheck) {
            $cartPreCheckResponseTransfer = $preCheck->check($cartChangeTransfer);
            if ($cartPreCheckResponseTransfer->getIsSuccess()) {
                continue;
            }

            $this->collectErrorsFromPreCheckResponse($cartPreCheckResponseTransfer);

            if ($preCheck instanceof TerminationAwareCartPreCheckPluginInterface && $preCheck->terminateOnFailure()) {
                return false;
            }

            $isCartValid = false;
        }

        return $isCartValid;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return bool
     */
    protected function executeCartRemovalPreCheckPlugins(CartChangeTransfer $cartChangeTransfer)
    {
        $isCartValid = true;
        foreach ($this->cartRemovalPreCheckPlugins as $cartRemovalPreCheckPlugin) {
            $cartPreCheckResponseTransfer = $cartRemovalPreCheckPlugin->check($cartChangeTransfer);
            if ($cartPreCheckResponseTransfer->getIsSuccess()) {
                continue;
            }

            $this->collectErrorsFromPreCheckResponse($cartPreCheckResponseTransfer);

            if ($cartRemovalPreCheckPlugin instanceof TerminationAwareCartPreCheckPluginInterface && $cartRemovalPreCheckPlugin->terminateOnFailure()) {
                return false;
            }

            $isCartValid = false;
        }

        return $isCartValid;
    }

    /**
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     *
     * @return void
     */
    protected function collectErrorsFromPreCheckResponse(CartPreCheckResponseTransfer $cartPreCheckResponseTransfer)
    {
        foreach ($cartPreCheckResponseTransfer->getMessages() as $messageTransfer) {
            $this->messengerFacade->addErrorMessage($messageTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer[] $infoMessages
     *
     * @return void
     */
    protected function addInfoMessages(array $infoMessages): void
    {
        foreach ($infoMessages as $message) {
            $this->messengerFacade->addInfoMessage($message);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    protected function getNotificationMessages(CartChangeTransfer $cartChangeTransfer): array
    {
        $notificationMessages = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $notificationMessages = array_merge(
                $notificationMessages,
                $this->getMessagesByTypeFromItemTransfer($itemTransfer, static::MESSAGE_TYPE_NOTIFICATION)
            );
        }

        return $notificationMessages;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $type
     *
     * @return array
     */
    protected function getMessagesByTypeFromItemTransfer(ItemTransfer $itemTransfer, string $type)
    {
        $messagesByType = [];

        foreach ($itemTransfer->getMessages() as $message) {
            if ($message->getType() === $type) {
                $messagesByType[] = $message;
            }
        }

        return $messagesByType;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function expandChangedItems(CartChangeTransfer $cartChangeTransfer)
    {
        foreach ($this->itemExpanderPlugins as $itemExpander) {
            $cartChangeTransfer = $itemExpander->expandItems($cartChangeTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executePostSavePlugins(QuoteTransfer $quoteTransfer)
    {
        foreach ($this->postSavePlugins as $postSavePlugin) {
            $quoteTransfer = $postSavePlugin->postSave($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executePreReloadPlugins(QuoteTransfer $quoteTransfer)
    {
        foreach ($this->preReloadPlugins as $reloadPlugin) {
            $quoteTransfer = $reloadPlugin->preReloadItems($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param string $message
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessengerMessageTransfer($message, array $parameters = [])
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($message);
        $messageTransfer->setParameters($parameters);

        return $messageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function recalculate(QuoteTransfer $quoteTransfer)
    {
        return $this->calculationFacade->recalculate($quoteTransfer);
    }

    /**
     * @param array $preReloadPlugins
     *
     * @return void
     */
    public function setPreReloadLoadPlugins(array $preReloadPlugins)
    {
        $this->preReloadPlugins = $preReloadPlugins;
    }
}
