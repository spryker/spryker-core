<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Business\Model;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface;
use Spryker\Zed\Cart\Dependency\Facade\CartToCalculationInterface;
use Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface;

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
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return bool
     */
    protected function preCheckCart(CartChangeTransfer $cartChangeTransfer)
    {
        foreach ($this->preCheckPlugins as $preCheck) {
            $cartPreCheckResponseTransfer = $preCheck->check($cartChangeTransfer);
            if ($cartPreCheckResponseTransfer->getIsSuccess()) {
                continue;
            }

            foreach ($cartPreCheckResponseTransfer->getMessages() as $messageTransfer) {
                $this->messengerFacade->addErrorMessage($messageTransfer);
            }

            return false;
        }

        return true;
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

}
