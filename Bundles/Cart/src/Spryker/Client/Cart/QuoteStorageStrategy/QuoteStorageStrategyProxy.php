<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\QuoteStorageStrategy;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Client\Cart\Dependency\Client\CartToMessengerClientInterface;
use Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface;

class QuoteStorageStrategyProxy implements QuoteStorageStrategyPluginInterface
{
    protected const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';
    protected const GLOSSARY_KEY_CHANGE_CURRENCY_FOR_QUOTE_DENIED = 'persistent_cart.quote.currency_change.denied';

    /**
     * @var \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface
     */
    protected $quoteStorageStrategy;

    /**
     * @var \Spryker\Client\Cart\Dependency\Client\CartToMessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @var \Spryker\Client\Cart\CartClientInterface
     */
    protected $cartClient;

    /**
     * @param \Spryker\Client\Cart\Dependency\Client\CartToMessengerClientInterface $messengerClient
     * @param \Spryker\Client\Cart\CartClientInterface $cartClient
     * @param \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface $quoteStorageStrategy
     */
    public function __construct(
        CartToMessengerClientInterface $messengerClient,
        CartClientInterface $cartClient,
        QuoteStorageStrategyPluginInterface $quoteStorageStrategy
    ) {
        $this->messengerClient = $messengerClient;
        $this->cartClient = $cartClient;
        $this->quoteStorageStrategy = $quoteStorageStrategy;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getStorageStrategy(): string
    {
        return $this->quoteStorageStrategy->getStorageStrategy();
    }

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
    public function addItem(ItemTransfer $itemTransfer, array $params = []): QuoteTransfer
    {
        if (!$this->isQuoteEditable()) {
            $this->addPermissionFailedMessage();

            return $this->getQuote();
        }

        return $this->quoteStorageStrategy->addItem($itemTransfer, $params);
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
    public function addItems(array $itemTransfers, array $params = []): QuoteTransfer
    {
        if (!$this->isQuoteEditable()) {
            $this->addPermissionFailedMessage();

            return $this->getQuote();
        }

        return $this->quoteStorageStrategy->addItems($itemTransfers, $params);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addValidItems(CartChangeTransfer $cartChangeTransfer, array $params = []): QuoteTransfer
    {
        if (!$this->isQuoteEditable()) {
            $this->addPermissionFailedMessage();

            return $this->getQuote();
        }

        return $this->quoteStorageStrategy->addValidItems($cartChangeTransfer, $params);
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
    public function removeItem($sku, $groupKey = null): QuoteTransfer
    {
        if (!$this->isQuoteEditable()) {
            $this->addPermissionFailedMessage();

            return $this->getQuote();
        }

        return $this->quoteStorageStrategy->removeItem($sku, $groupKey);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItems(ArrayObject $items): QuoteTransfer
    {
        if (!$this->isQuoteEditable()) {
            $this->addPermissionFailedMessage();

            return $this->getQuote();
        }

        return $this->quoteStorageStrategy->removeItems($items);
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
    public function changeItemQuantity($sku, $groupKey = null, $quantity = 1): QuoteTransfer
    {
        if (!$this->isQuoteEditable()) {
            $this->addPermissionFailedMessage();

            return $this->getQuote();
        }

        return $this->quoteStorageStrategy->changeItemQuantity($sku, $groupKey, $quantity);
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
    public function decreaseItemQuantity($sku, $groupKey = null, $quantity = 1): QuoteTransfer
    {
        if (!$this->isQuoteEditable()) {
            $this->addPermissionFailedMessage();

            return $this->getQuote();
        }

        return $this->quoteStorageStrategy->decreaseItemQuantity($sku, $groupKey, $quantity);
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
    public function increaseItemQuantity($sku, $groupKey = null, $quantity = 1): QuoteTransfer
    {
        if (!$this->isQuoteEditable()) {
            $this->addPermissionFailedMessage();

            return $this->getQuote();
        }

        return $this->quoteStorageStrategy->increaseItemQuantity($sku, $groupKey, $quantity);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function reloadItems(): void
    {
        if (!$this->isQuoteEditable()) {
            $this->addPermissionFailedMessage();
        }

        $this->quoteStorageStrategy->reloadItems();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateQuote(): QuoteResponseTransfer
    {
        if (!$this->isQuoteEditable()) {
            return $this->createNotSuccessfulQuoteResponseTransfer();
        }

        return $this->quoteStorageStrategy->validateQuote();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setQuoteCurrency(CurrencyTransfer $currencyTransfer): QuoteResponseTransfer
    {
        if (!$this->isQuoteEditable()) {
            $this->messengerClient->addErrorMessage(static::GLOSSARY_KEY_CHANGE_CURRENCY_FOR_QUOTE_DENIED);

            return $this->createNotSuccessfulQuoteResponseTransfer();
        }

        return $this->quoteStorageStrategy->setQuoteCurrency($currencyTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createNotSuccessfulQuoteResponseTransfer(): QuoteResponseTransfer
    {
        $quoteResponseTransfer = new QuoteResponseTransfer();

        $quoteResponseTransfer->setIsSuccessful(false);
        $quoteResponseTransfer->setQuoteTransfer($this->cartClient->getQuote());
        $quoteResponseTransfer->setCustomer($this->cartClient->getQuote()->getCustomer());

        return $quoteResponseTransfer;
    }

    /**
     * @return void
     */
    protected function addPermissionFailedMessage(): void
    {
        $this->messengerClient->addErrorMessage(static::GLOSSARY_KEY_PERMISSION_FAILED);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuote(): QuoteTransfer
    {
        return $this->cartClient->getQuote();
    }

    /**
     * @return bool
     */
    protected function isQuoteEditable(): bool
    {
        return $this->cartClient->isQuoteEditable();
    }
}
