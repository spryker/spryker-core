<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface;
use Spryker\Zed\Cart\Dependency\Facade\CartToCalculationInterface;
use Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface;
use Spryker\Zed\Cart\Dependency\TerminationAwareCartPreCheckPluginInterface;

class Operation implements OperationInterface
{
    const ADD_ITEMS_SUCCESS = 'cart.add.items.success';
    const REMOVE_ITEMS_SUCCESS = 'cart.remove.items.success';

    protected const TERMINATION_EVENT_NAME_ADD = 'add';
    protected const TERMINATION_EVENT_NAME_REMOVE = 'remove';
    protected const TERMINATION_EVENT_NAME_RELOAD = 'reload';

    /**
     * @var \Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface
     */
    protected $cartStorageProvider;

    /**
     * @var \Spryker\Zed\Cart\Dependency\Facade\CartToCalculationInterface
     */
    protected $calculationFacade;

    /**
     * @var \Spryker\Zed\Messenger\Business\MessengerFacade
     */
    protected $messengerFacade;

    /**
     * @var \Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface[]
     */
    protected $itemExpanderPlugins = [];

    /**
     * @var \Spryker\Zed\Cart\Dependency\CartPreCheckPluginInterface[]
     */
    protected $preCheckPlugins;

    /**
     * @var \Spryker\Zed\CartExtension\Dependency\Plugin\CartRemovalPreCheckPluginInterface[]
     */
    protected $cartRemovalPreCheckPlugins;

    /**
     * @var \Spryker\Zed\Cart\Dependency\PostSavePluginInterface[]
     */
    protected $postSavePlugins = [];

    /**
     * @var \Spryker\Zed\Cart\Dependency\PreReloadItemsPluginInterface[]
     */
    protected $preReloadPlugins = [];

    /**
     * @var \Spryker\Zed\CartExtension\Dependency\Plugin\CartTerminationPluginInterface[]
     */
    protected $terminationPlugins = [];

    /**
     * @param \Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface $cartStorageProvider
     * @param \Spryker\Zed\Cart\Dependency\Facade\CartToCalculationInterface $calculationFacade
     * @param \Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface $messengerFacade
     * @param \Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface[] $itemExpanderPlugins
     * @param \Spryker\Zed\Cart\Dependency\CartPreCheckPluginInterface[] $preCheckPlugins
     * @param \Spryker\Zed\Cart\Dependency\PostSavePluginInterface[] $postSavePlugins
     * @param \Spryker\Zed\CartExtension\Dependency\Plugin\CartTerminationPluginInterface[] $terminationPlugins
     * @param \Spryker\Zed\Cart\Dependency\CartPreCheckPluginInterface[] $cartRemovalPreCheckPlugins
     */
    public function __construct(
        StorageProviderInterface $cartStorageProvider,
        CartToCalculationInterface $calculationFacade,
        CartToMessengerInterface $messengerFacade,
        array $itemExpanderPlugins,
        array $preCheckPlugins,
        array $postSavePlugins,
        array $terminationPlugins,
        array $cartRemovalPreCheckPlugins
    ) {
        $this->cartStorageProvider = $cartStorageProvider;
        $this->calculationFacade = $calculationFacade;
        $this->messengerFacade = $messengerFacade;
        $this->itemExpanderPlugins = $itemExpanderPlugins;
        $this->preCheckPlugins = $preCheckPlugins;
        $this->postSavePlugins = $postSavePlugins;
        $this->terminationPlugins = $terminationPlugins;
        $this->cartRemovalPreCheckPlugins = $cartRemovalPreCheckPlugins;
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
        $itemsTransfer = $cartChangeTransfer->getItems();

        foreach ($itemsTransfer as $currentItemTransfer) {
            $itemsCollection = new ArrayObject([$currentItemTransfer]);
            $currentCartChangeTransfer = new CartChangeTransfer();
            $currentCartChangeTransfer->setQuote($quoteTransfer);
            $currentCartChangeTransfer->setItems($itemsCollection);

            $quoteTransfer = $this->add($currentCartChangeTransfer);
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
        $cartChangeTransfer->requireQuote();

        $originalQuoteTransfer = (new QuoteTransfer())->fromArray($cartChangeTransfer->getQuote()->modifiedToArray(), true);

        if (!$this->preCheckCart($cartChangeTransfer)) {
            return $cartChangeTransfer->getQuote();
        }

        $expandedCartChangeTransfer = $this->expandChangedItems($cartChangeTransfer);
        $quoteTransfer = $this->cartStorageProvider->addItems($expandedCartChangeTransfer);
        $quoteTransfer = $this->executePostSavePlugins($quoteTransfer);
        $quoteTransfer = $this->recalculate($quoteTransfer);

        if ($this->isTerminated(static::TERMINATION_EVENT_NAME_ADD, $cartChangeTransfer, $quoteTransfer)) {
            return $originalQuoteTransfer;
        }

        $this->messengerFacade->addSuccessMessage($this->createMessengerMessageTransfer(self::ADD_ITEMS_SUCCESS));

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function remove(CartChangeTransfer $cartChangeTransfer)
    {
        $cartChangeTransfer->requireQuote();

        $originalQuoteTransfer = (new QuoteTransfer())->fromArray($cartChangeTransfer->getQuote()->modifiedToArray(), true);

        if (!$this->executeCartRemovalPreCheckPlugins($cartChangeTransfer)) {
            return $cartChangeTransfer->getQuote();
        }

        $expandedCartChangeTransfer = $this->expandChangedItems($cartChangeTransfer);
        $quoteTransfer = $this->cartStorageProvider->removeItems($expandedCartChangeTransfer);
        $quoteTransfer = $this->executePostSavePlugins($quoteTransfer);
        $quoteTransfer = $this->recalculate($quoteTransfer);

        if ($this->isTerminated(static::TERMINATION_EVENT_NAME_REMOVE, $cartChangeTransfer, $quoteTransfer)) {
            return $originalQuoteTransfer;
        }

        $this->messengerFacade->addSuccessMessage($this->createMessengerMessageTransfer(self::REMOVE_ITEMS_SUCCESS));

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function reloadItems(QuoteTransfer $quoteTransfer)
    {
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

        if ($this->isTerminated(static::TERMINATION_EVENT_NAME_RELOAD, $cartChangeTransfer, $quoteTransfer)) {
            return $originalQuoteTransfer;
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
