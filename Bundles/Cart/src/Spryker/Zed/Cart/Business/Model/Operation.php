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
     * @var \Spryker\Zed\Cart\Dependency\PostSavePluginInterface[]
     */
    protected $postSavePlugins = [];

    /**
     * @var \Spryker\Zed\Cart\Dependency\PreReloadItemsPluginInterface[]
     */
    protected $preReloadPlugins = [];

    /**
     * @param \Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface $cartStorageProvider
     * @param \Spryker\Zed\Cart\Dependency\Facade\CartToCalculationInterface $calculationFacade
     * @param \Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface $messengerFacade
     * @param \Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface[] $itemExpanderPlugins
     * @param \Spryker\Zed\Cart\Dependency\CartPreCheckPluginInterface[] $preCheckPlugins
     * @param \Spryker\Zed\Cart\Dependency\PostSavePluginInterface[] $postSavePlugins
     */
    public function __construct(
        StorageProviderInterface $cartStorageProvider,
        CartToCalculationInterface $calculationFacade,
        CartToMessengerInterface $messengerFacade,
        array $itemExpanderPlugins,
        array $preCheckPlugins,
        array $postSavePlugins
    ) {
        $this->cartStorageProvider = $cartStorageProvider;
        $this->calculationFacade = $calculationFacade;
        $this->messengerFacade = $messengerFacade;
        $this->itemExpanderPlugins = $itemExpanderPlugins;
        $this->preCheckPlugins = $preCheckPlugins;
        $this->postSavePlugins = $postSavePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function add(CartChangeTransfer $cartChangeTransfer)
    {
        if (!$this->preCheckCart($cartChangeTransfer)) {
            return $cartChangeTransfer->getQuote();
        }

        $expandedCartChangeTransfer = $this->expandChangedItems($cartChangeTransfer);
        $quoteTransfer = $this->cartStorageProvider->addItems($expandedCartChangeTransfer);
        $quoteTransfer = $this->executePostSavePlugins($quoteTransfer);
        $this->messengerFacade->addSuccessMessage($this->createMessengerMessageTransfer(self::ADD_ITEMS_SUCCESS));

        return $this->recalculate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function remove(CartChangeTransfer $cartChangeTransfer)
    {
        $expandedCartChangeTransfer = $this->expandChangedItems($cartChangeTransfer);
        $quoteTransfer = $this->cartStorageProvider->removeItems($expandedCartChangeTransfer);
        $quoteTransfer = $this->executePostSavePlugins($quoteTransfer);
        $this->messengerFacade->addSuccessMessage($this->createMessengerMessageTransfer(self::REMOVE_ITEMS_SUCCESS));

        return $this->recalculate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function reloadItems(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer = $this->executePreReloadPlugins($quoteTransfer);

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setItems($quoteTransfer->getItems());

        $quoteTransfer->setItems(new ArrayObject());

        $cartChangeTransfer->setQuote($quoteTransfer);

        if (!$this->preCheckCart($cartChangeTransfer)) {
            return $cartChangeTransfer->getQuote();
        }

        $expandedCartChangeTransfer = $this->expandChangedItems($cartChangeTransfer);
        $quoteTransfer = $this->cartStorageProvider->addItems($expandedCartChangeTransfer);
        $quoteTransfer = $this->executePostSavePlugins($quoteTransfer);

        return $this->recalculate($quoteTransfer);
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
     */
    public function setPreReloadLoadPlugins(array $preReloadPlugins)
    {
        $this->preReloadPlugins = $preReloadPlugins;
    }

}
