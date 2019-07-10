<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PriceCartConnector\Business\Exception\PriceMissingException;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface;

class PriceBulkManager implements PriceManagerInterface
{
    protected const ERROR_MESSAGE_NOT_EXISTING_PRICE = 'Cart item "%s" can not be priced.';

    /**
     * @var string
     */
    protected static $netPriceModeIdentifier;

    /**
     * @var string
     */
    protected static $defaultPriceType;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface
     */
    protected $priceFacade;

    /**
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface $priceProductFacade
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface $priceFacade
     */
    public function __construct(
        PriceCartToPriceProductInterface $priceProductFacade,
        PriceCartToPriceInterface $priceFacade
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->priceFacade = $priceFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function addPriceToItems(CartChangeTransfer $cartChangeTransfer)
    {
        $cartChangeTransfer->setQuote(
            $this->setQuotePriceMode($cartChangeTransfer->getQuote())
        );
        $priceMode = $cartChangeTransfer->getQuote()->getPriceMode();

        $priceProductFilterTransfers = $this->createPriceProductFilterTransfers($cartChangeTransfer->getQuote());
        $priceProductTransfers = $this->priceProductFacade->getValidPrices($priceProductFilterTransfers);
        $priceProductTransfers = $this->indexPriceProductTransfersBySku($priceProductTransfers);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $priceProductTransfer = $this->getPriceProductTransferBySku($priceProductTransfers, $itemTransfer->getSku());

            $itemTransfer = $this->setOriginUnitPrices($itemTransfer, $priceProductTransfer, $priceMode);

            if ($this->hasSourceUnitPrices($itemTransfer)) {
                $itemTransfer = $this->applySourceUnitPrices($itemTransfer);
                continue;
            }

            $itemTransfer = $this->applyOriginUnitPrices($itemTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param string $sku
     *
     * @throws \Spryker\Zed\PriceCartConnector\Business\Exception\PriceMissingException
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function getPriceProductTransferBySku(array $priceProductTransfers, string $sku): PriceProductTransfer
    {
        if (!isset($priceProductTransfers[$sku])) {
            throw new PriceMissingException(
                sprintf(
                    static::ERROR_MESSAGE_NOT_EXISTING_PRICE,
                    $sku
                )
            );
        }

        return $priceProductTransfers[$sku];
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function indexPriceProductTransfersBySku(array $priceProductTransfers): array
    {
        $indexedPriceProductTransfers = [];
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $indexedPriceProductTransfers[$priceProductTransfer->getSkuProduct()] = $priceProductTransfer;
        }

        return $indexedPriceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer[]
     */
    protected function createPriceProductFilterTransfers(QuoteTransfer $quoteTransfer): array
    {
        $priceProductFilterTransfers = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $priceProductFilterTransfers[] = $this->createPriceProductFilterTransfer($itemTransfer, $quoteTransfer);
        }

        return $priceProductFilterTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function setOriginUnitPrices(ItemTransfer $itemTransfer, PriceProductTransfer $priceProductTransfer, string $priceMode): ItemTransfer
    {
        $itemTransfer->setPriceProduct($priceProductTransfer);

        if ($priceMode === $this->getNetPriceModeIdentifier()) {
            $itemTransfer->setOriginUnitNetPrice($priceProductTransfer->getMoneyValue()->getNetAmount());
            $itemTransfer->setOriginUnitGrossPrice(0);
            $itemTransfer->setSumGrossPrice(0);

            return $itemTransfer;
        }

        $itemTransfer->setOriginUnitNetPrice(0);
        $itemTransfer->setOriginUnitGrossPrice($priceProductTransfer->getMoneyValue()->getGrossAmount());
        $itemTransfer->setSumNetPrice(0);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function applySourceUnitPrices(ItemTransfer $itemTransfer): ItemTransfer
    {
        if ($itemTransfer->getSourceUnitNetPrice() !== null) {
            $itemTransfer->setUnitNetPrice($itemTransfer->getSourceUnitNetPrice());
            $itemTransfer->setUnitGrossPrice(0);
        }

        if ($itemTransfer->getSourceUnitGrossPrice() !== null) {
            $itemTransfer->setUnitNetPrice(0);
            $itemTransfer->setUnitGrossPrice($itemTransfer->getSourceUnitGrossPrice());
        }

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function applyOriginUnitPrices(ItemTransfer $itemTransfer): ItemTransfer
    {
        $itemTransfer->setUnitNetPrice($itemTransfer->getOriginUnitNetPrice());
        $itemTransfer->setUnitGrossPrice($itemTransfer->getOriginUnitGrossPrice());

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function hasSourceUnitPrices(ItemTransfer $itemTransfer): bool
    {
        if ($itemTransfer->getSourceUnitGrossPrice() !== null) {
            return true;
        }

        if ($itemTransfer->getSourceUnitNetPrice() !== null) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setQuotePriceMode(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$quoteTransfer->getPriceMode()) {
            $quoteTransfer->setPriceMode($this->priceFacade->getDefaultPriceMode());
        }

        return $quoteTransfer;
    }

    /**
     * @return string
     */
    protected function getNetPriceModeIdentifier(): string
    {
        if (!static::$netPriceModeIdentifier) {
            static::$netPriceModeIdentifier = $this->priceFacade->getNetPriceModeIdentifier();
        }

        return static::$netPriceModeIdentifier;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function createPriceProductFilterTransfer(
        ItemTransfer $itemTransfer,
        QuoteTransfer $quoteTransfer
    ): PriceProductFilterTransfer {
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())->fromArray($itemTransfer->toArray(), true);

        $priceProductFilterTransfer
            ->setStoreName($this->findStoreName($quoteTransfer))
            ->setPriceMode($quoteTransfer->getPriceMode())
            ->setCurrencyIsoCode($quoteTransfer->getCurrency()->getCode())
            ->setPriceTypeName($this->priceProductFacade->getDefaultPriceTypeName());

        if ($this->isPriceProductDimensionEnabled($priceProductFilterTransfer)) {
            $priceProductFilterTransfer->setQuote($quoteTransfer);
        }

        return $priceProductFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return bool
     */
    protected function isPriceProductDimensionEnabled(PriceProductFilterTransfer $priceProductFilterTransfer): bool
    {
        return property_exists($priceProductFilterTransfer, 'quote');
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    protected function findStoreName(QuoteTransfer $quoteTransfer): ?string
    {
        if ($quoteTransfer->getStore() === null) {
            return null;
        }

        return $quoteTransfer->getStore()->getName();
    }
}
