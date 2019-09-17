<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Client\CartExtension\Dependency\Plugin\QuoteResetLockQuoteStorageStrategyPluginInterface;
use Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\Quote\QuoteConfig;

/**
 * @method \Spryker\Client\PersistentCart\PersistentCartFactory getFactory()
 * @method \Spryker\Client\PersistentCart\PersistentCartClientInterface getClient()
 */
class DatabaseQuoteStorageStrategy extends AbstractPlugin implements QuoteStorageStrategyPluginInterface, QuoteResetLockQuoteStorageStrategyPluginInterface
{
    /**
     * @return string
     */
    public function getStorageStrategy()
    {
        return QuoteConfig::STORAGE_STRATEGY_DATABASE;
    }

    /**
     * Specification:
     *  - Makes zed request with item and customer.
     *  - Loads customer quote from database.
     *  - Merges loaded quote with quote from session.
     *  - Adds item to quote.
     *  - Recalculates quote totals.
     *  - Save updated quote to database.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
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
     * Specification:
     *  - Makes zed request with items and customer.
     *  - Loads customer quote from database.
     *  - Merges loaded quote with quote from session.
     *  - Adds items to quote.
     *  - Recalculates quote totals.
     *  - Saves updated quote to database.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
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
     * Specification:
     *  - Makes zed request with items and customer.
     *  - Loads customer quote from database.
     *  - Merges loaded quote with quote from session.
     *  - Adds only items, that passed validation.
     *  - Recalculates quote totals.
     *  - Save updated quote to database.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addValidItems(CartChangeTransfer $cartChangeTransfer, array $params = []): QuoteTransfer
    {
        $persistentCartChangeTransfer = $this->createPersistentCartChangeTransfer();
        $persistentCartChangeTransfer->setIdQuote($cartChangeTransfer->getQuote()->getIdQuote());

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $persistentCartChangeTransfer->addItem($itemTransfer);
        }

        $persistentCartChangeTransfer = $this->getFactory()
            ->createChangeRequestExtendPluginExecutor()
            ->executePlugins($persistentCartChangeTransfer, $params);

        $quoteResponseTransfer = $this->getZedStub()->addValidItems($persistentCartChangeTransfer);

        return $this->updateQuote($quoteResponseTransfer);
    }

    /**
     * Specification:
     *  - Makes zed request with items and customer.
     *  - Loads customer quote from database.
     *  - Merges loaded quote with quote from session.
     *  - Removes single item from quote.
     *  - Recalculates quote totals.
     *  - Save updated quote to database.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
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
     * Specification:
     *  - Makes zed request with items and customer.
     *  - Loads customer quote from database.
     *  - Merges loaded quote with quote from session.
     *  - Removes items from quote.
     *  - Recalculates quote totals.
     *  - Save updated quote to database.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
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
     * Specification:
     *  - Makes zed request with items and customer.
     *  - Loads customer quote from database.
     *  - Changes quantity for given item.
     *  - Recalculates quote totals.
     *  - Save updated quote to database.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
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
     * Specification:
     *  - Makes zed request with items and customer.
     *  - Loads customer quote from database.
     *  - Changes quantity for given items.
     *  - Recalculates quote totals.
     *  - Save updated quote to database.
     *  - Stores quote in session internally after zed request.
     *  - Returns quote response.
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuantity(CartChangeTransfer $cartChangeTransfer): QuoteResponseTransfer
    {
        $persistentCartChangeTransfer = $this->createPersistentCartChangeTransfer()
            ->setItems($cartChangeTransfer->getItems());

        $quoteResponseTransfer = $this->getZedStub()->updateQuantity($persistentCartChangeTransfer);
        $this->updateQuote($quoteResponseTransfer);

        return $quoteResponseTransfer;
    }

    /**
     * Specification:
     *  - Makes zed request with items and customer.
     *  - Loads customer quote from database.
     *  - Decreases quantity for given item.
     *  - Recalculates quote totals.
     *  - Save updated quote to database.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
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
     * Specification:
     *  - Makes zed request with items and customer.
     *  - Loads customer quote from database.
     *  - Increases quantity for given item.
     *  - Recalculates quote totals.
     *  - Save updated quote to database.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
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
     * Specification:
     *  - Makes zed request.
     *  - Reloads all items in cart anew, it recreates all items transfer, reads new prices, options, bundles.
     *  - Recalculates quote totals.
     *  - Save updated quote to database.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
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
     *  - Makes zed request.
     *  - Reloads quote from storage.
     *  - Reloads all items in cart anew, it recreates all items transfer, reads new prices, options, bundles.
     *  - If quote is locked returns quote, ignores following steps.
     *  - Adds changes as notices to messages.
     *  - Checks error messages.
     *  - Recalculates quote totals.
     *  - Saves updated quote to database.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
     *
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
     * Specification:
     *  - Makes zed request.
     *  - Reloads all items in cart anew, it recreates all items transfer, reads new prices, options, bundles.
     *  - Recalculates quote totals.
     *  - Saves updated quote to database.
     *  - Stores quote in session internally after zed request.
     *  - Returns update quote.
     *
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setQuoteCurrency(CurrencyTransfer $currencyTransfer): QuoteResponseTransfer
    {
        $quoteUpdateRequestTransfer = new QuoteUpdateRequestTransfer();
        $quoteUpdateRequestTransfer->setIdQuote($this->getQuoteClient()->getQuote()->getIdQuote());
        $quoteUpdateRequestTransfer->setCustomer($this->getFactory()->getCustomerClient()->getCustomer());
        $quoteUpdateRequestAttributesTransfer = new QuoteUpdateRequestAttributesTransfer();
        $quoteUpdateRequestAttributesTransfer->setCurrency($currencyTransfer);
        $quoteUpdateRequestTransfer->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributesTransfer);

        $quoteResponseTransfer = $this->getZedStub()->updateAndReloadQuote($quoteUpdateRequestTransfer);
        $this->updateQuote($quoteResponseTransfer);

        return $quoteResponseTransfer;
    }

    /**
     * Specification:
     * - Makes zed request.
     * - Loads customer quote from database.
     * - Executes QuoteLockPreResetPluginInterface plugins before unlock.
     * - Unlocks quote by setting `isLocked` transfer property to false.
     * - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles.
     * - Save updated quote to database.
     * - Stores quote in session internally after zed request.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function resetQuoteLock(): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->getZedStub()
            ->resetQuoteLock($this->getQuoteClient()->getQuote());

        $this->getQuoteClient()->setQuote($quoteResponseTransfer->getQuoteTransfer());

        return $quoteResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    protected function createPersistentCartChangeTransfer(): PersistentCartChangeTransfer
    {
        $persistentQuoteChange = new PersistentCartChangeTransfer();
        $persistentQuoteChange->setCustomer($this->getFactory()->getCustomerClient()->getCustomer());

        $sessionQuoteTransfer = $this->getQuoteClient()->getQuote();
        $persistentQuoteChange->setIdQuote($sessionQuoteTransfer->getIdQuote());
        $persistentQuoteChange->setQuote($sessionQuoteTransfer);

        return $persistentQuoteChange;
    }

    /**
     * @return \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer
     */
    protected function createPersistentCartChangeQuantityTransfer(): PersistentCartChangeQuantityTransfer
    {
        $persistentQuoteChange = new PersistentCartChangeQuantityTransfer();
        $persistentQuoteChange->setCustomer($this->getFactory()->getCustomerClient()->getCustomer());

        $sessionQuoteTransfer = $this->getQuoteClient()->getQuote();
        $persistentQuoteChange->setIdQuote($sessionQuoteTransfer->getIdQuote());
        $persistentQuoteChange->setQuote($sessionQuoteTransfer);

        return $persistentQuoteChange;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function updateQuote(QuoteResponseTransfer $quoteResponseTransfer)
    {
        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $sessionQuoteTransfer = $this->getQuoteClient()->getQuote();

        if (!$quoteTransfer) {
            return new QuoteTransfer();
        }

        if ($quoteTransfer->getIdQuote() === $sessionQuoteTransfer->getIdQuote()) {
            $quoteTransfer = $sessionQuoteTransfer->fromArray(
                $quoteTransfer->modifiedToArray(),
                true
            );
        }

        $this->getQuoteClient()->setQuote($quoteTransfer);
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
