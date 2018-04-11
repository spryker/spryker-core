<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\Quote\QuoteConfig;

/**
 * @method \Spryker\Client\PersistentCart\PersistentCartFactory getFactory()
 */
class DatabaseQuoteStorageStrategy extends AbstractPlugin implements QuoteStorageStrategyPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItem(ItemTransfer $itemTransfer, array $params = [])
    {
        $persistentCartChangeTransfer = $this->createPersistentCartChangeTransfer();
        $persistentCartChangeTransfer->addItem($itemTransfer);
        $persistentCartChangeTransfer = $this->getFactory()
            ->createChangeRequestExtendPluginExecutor()
            ->executePlugins($persistentCartChangeTransfer, $params);

        $quoteResponseTransfer = $this->getZedStub()->addItem($persistentCartChangeTransfer);

        return $this->updateQuote($quoteResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItems(array $itemTransfers, array $params = [])
    {
        $persistentCartChangeTransfer = $this->createPersistentCartChangeTransfer();
        
        foreach ($itemTransfers as $itemTransfer) {
            $persistentCartChangeTransfer->addItem($itemTransfer);
        }

        $persistentCartChangeTransfer = $this->getFactory()
            ->createChangeRequestExtendPluginExecutor()
            ->executePlugins($persistentCartChangeTransfer, $params);

        $quoteResponseTransfer = $this->getZedStub()->addItem($persistentCartChangeTransfer);

        return $this->updateQuote($quoteResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addValidItems(CartChangeTransfer $cartChangeTransfer, array $params = []): QuoteTransfer
    {
        $persistentCartChangeTransfer = $this->createPersistentCartChangeTransfer();

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $persistentCartChangeTransfer->addItem($itemTransfer);
        }

        $persistentCartChangeTransfer = $this->getFactory()
            ->createChangeRequestExtendPluginExecutor()
            ->executePlugins($persistentCartChangeTransfer, $params);

        $quoteResponseTransfer = $this->getZedStub()->addItem($persistentCartChangeTransfer);

        return $this->updateQuote($quoteResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItem($sku, $groupKey = null)
    {
        $persistentCartChangeTransfer = $this->createPersistentCartChangeTransfer();
        $itemTransfer = new ItemTransfer();
        $itemTransfer
            ->setSku($sku)
            ->setGroupKey($groupKey);
        $persistentCartChangeTransfer->addItem($itemTransfer);
        $quoteResponseTransfer = $this->getZedStub()->removeItem($persistentCartChangeTransfer);

        return $this->updateQuote($quoteResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItems(ArrayObject $itemTransfers)
    {
        $persistentCartChangeTransfer = $this->createPersistentCartChangeTransfer();
        foreach ($itemTransfers as $itemTransfer) {
            $persistentCartChangeTransfer->addItem($itemTransfer);
        }
        $quoteResponseTransfer = $this->getZedStub()->removeItem($persistentCartChangeTransfer);

        return $this->updateQuote($quoteResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function changeItemQuantity($sku, $groupKey = null, $quantity = 1)
    {
        $persistentCartChangeTransfer = $this->createPersistentCartChangeQuantityTransfer();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($sku);
        $itemTransfer->setGroupKey($groupKey);
        $itemTransfer->setQuantity($quantity);
        $persistentCartChangeTransfer->setItem($itemTransfer);
        $quoteResponseTransfer = $this->getZedStub()->changeItemQuantity($persistentCartChangeTransfer);

        return $this->updateQuote($quoteResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decreaseItemQuantity($sku, $groupKey = null, $quantity = 1)
    {
        $persistentCartChangeTransfer = $this->createPersistentCartChangeQuantityTransfer();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($sku);
        $itemTransfer->setGroupKey($groupKey);
        $itemTransfer->setQuantity($quantity);
        $persistentCartChangeTransfer->setItem($itemTransfer);
        $quoteResponseTransfer = $this->getZedStub()->decreaseItemQuantity($persistentCartChangeTransfer);

        return $this->updateQuote($quoteResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increaseItemQuantity($sku, $groupKey = null, $quantity = 1)
    {
        $persistentCartChangeTransfer = $this->createPersistentCartChangeQuantityTransfer();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($sku);
        $itemTransfer->setGroupKey($groupKey);
        $itemTransfer->setQuantity($quantity);
        $persistentCartChangeTransfer->setItem($itemTransfer);
        $quoteResponseTransfer = $this->getZedStub()->increaseItemQuantity($persistentCartChangeTransfer);

        return $this->updateQuote($quoteResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function reloadItems()
    {
        $quoteTransfer = $this->getQuoteClient()->getQuote();
        $quoteResponseTransfer = $this->getZedStub()->reloadItems($quoteTransfer);
        $this->updateQuote($quoteResponseTransfer);
    }

    /**
     * Specification:
     * - Gets quote storage strategy type
     *
     * @api
     *
     * @return string
     */
    public function getStorageStrategy()
    {
        return QuoteConfig::STORAGE_STRATEGY_DATABASE;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateQuote()
    {
        $quoteTransfer = $this->getQuoteClient()->getQuote();
        $quoteTransfer->setCustomer($this->getFactory()->getCustomerClient()->getCustomer());
        $quoteResponseTransfer = $this->getZedStub()->validateQuote($quoteTransfer);
        $this->updateQuote($quoteResponseTransfer);
        return $quoteResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    protected function createPersistentCartChangeTransfer()
    {
        $persistentQuoteChange = new PersistentCartChangeTransfer();
        $persistentQuoteChange->setCustomer($this->getFactory()->getCustomerClient()->getCustomer());
        $persistentQuoteChange->setIdQuote($this->getQuoteClient()->getQuote()->getIdQuote());

        return $persistentQuoteChange;
    }

    /**
     * @return \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer
     */
    protected function createPersistentCartChangeQuantityTransfer()
    {
        $persistentQuoteChange = new PersistentCartChangeQuantityTransfer();
        $persistentQuoteChange->setCustomer($this->getFactory()->getCustomerClient()->getCustomer());
        $persistentQuoteChange->setIdQuote($this->getQuoteClient()->getQuote()->getIdQuote());

        return $persistentQuoteChange;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function updateQuote(QuoteResponseTransfer $quoteResponseTransfer)
    {
        $sessionQuoteTransfer = $this->getQuoteClient()->getQuote();
        $sessionQuoteTransfer->fromArray(
            $quoteResponseTransfer->getQuoteTransfer()->modifiedToArray(),
            true
        );
        $this->getQuoteClient()->setQuote($sessionQuoteTransfer);
        $this->executeUpdateQuotePlugins($quoteResponseTransfer);

        return $sessionQuoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function executeUpdateQuotePlugins(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()->createQuoteUpdatePluginExecutor()->executePlugins($quoteResponseTransfer);
    }

    /**
     * @return \Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface
     */
    protected function getZedStub()
    {
        return $this->getFactory()->createZedPersistentCartStub();
    }

    /**
     * @return \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface
     */
    protected function getQuoteClient()
    {
        return $this->getFactory()->getQuoteClient();
    }
}
