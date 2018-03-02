<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\Dependency\Plugin\QuoteStorageStrategyPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface;
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
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItem(ItemTransfer $itemTransfer)
    {
        $persistentCartChangeTransfer = $this->createPersistentCartChangeTransfer();
        $persistentCartChangeTransfer->addItem($itemTransfer);
        $quoteTransfer = $this->getZedStub()->addItem($persistentCartChangeTransfer);
        $this->saveQuote($quoteTransfer);
        return $quoteTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItems(array $itemTransfers)
    {
        $persistentCartChangeTransfer = $this->createPersistentCartChangeTransfer();
        foreach ($itemTransfers as $itemTransfer) {
            $persistentCartChangeTransfer->addItem($itemTransfer);
        }
        $quoteTransfer = $this->getZedStub()->addItem($persistentCartChangeTransfer);
        $this->saveQuote($quoteTransfer);
        return $quoteTransfer;
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
        $itemTransfer->setSku($sku);
        $itemTransfer->setGroupKey($groupKey);
        $persistentCartChangeTransfer->addItem($itemTransfer);
        $quoteTransfer = $this->getZedStub()->removeItem($persistentCartChangeTransfer);
        $this->saveQuote($quoteTransfer);
        return $quoteTransfer;
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
        $quoteTransfer = $this->getZedStub()->removeItem($persistentCartChangeTransfer);
        $this->saveQuote($quoteTransfer);
        return $quoteTransfer;
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
        // TODO: Implement changeItemQuantity() method.
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
        // TODO: Implement decreaseItemQuantity() method.
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
        // TODO: Implement increaseItemQuantity() method.
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
        // TODO: Implement reloadItems() method.
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
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    protected function createPersistentCartChangeTransfer()
    {
        $persistentQuoteChange = new PersistentCartChangeTransfer();
        $persistentQuoteChange->setCustomer($this->getFactory()->getCustomerClient()->getCustomer());

        return $persistentQuoteChange;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function saveQuote(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->getQuoteClient()->setQuote($quoteTransfer);
    }

    /**
     * @return \Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface
     */
    protected function getZedStub(): PersistentCartStubInterface
    {
        return $this->getFactory()->createZedPersistentCartStub();
    }
}
