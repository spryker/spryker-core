<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PriceCartConnector\Business\Exception\PriceMissingException;
use Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface;

class PriceManager implements PriceManagerInterface
{
    protected const ERROR_MESSAGE_CART_ITEM_CAN_NOT_BE_PRICED = 'Cart item "%s" can not be priced.';

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
     * @var \Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface
     */
    protected $priceProductFilter;

    /**
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface $priceProductFacade
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface $priceFacade
     * @param \Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface $priceProductFilter
     */
    public function __construct(
        PriceCartToPriceProductInterface $priceProductFacade,
        PriceCartToPriceInterface $priceFacade,
        PriceProductFilterInterface $priceProductFilter
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->priceFacade = $priceFacade;
        $this->priceProductFilter = $priceProductFilter;
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

        $priceProductFilterTransfers = $this->createPriceProductFilterTransfers($cartChangeTransfer);
        $priceProductTransfers = $this->getIndexedPriceProductTransfersByPriceProductFilterTransfers($priceProductFilterTransfers);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $priceProductTransfer = $this->getPriceProductTransferBySku($priceProductTransfers, $itemTransfer->getSku());
            $itemTransfer = $this->setOriginUnitPrices($itemTransfer, $priceProductTransfer, $priceMode);

            if ($this->hasForcedUnitGrossPrice($itemTransfer)) {
                continue;
            }

            if ($this->hasSourceUnitPrices($itemTransfer)) {
                $itemTransfer = $this->applySourceUnitPrices($itemTransfer);
                continue;
            }

            $itemTransfer = $this->applyOriginUnitPrices($itemTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer[] $priceProductFilterTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function getIndexedPriceProductTransfersByPriceProductFilterTransfers(array $priceProductFilterTransfers): array
    {
        $priceProductTransfers = $this->priceProductFacade->getValidPrices($priceProductFilterTransfers);

        return $this->indexPriceProductTransfersBySku($priceProductTransfers);
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
                    static::ERROR_MESSAGE_CART_ITEM_CAN_NOT_BE_PRICED,
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
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer[]
     */
    protected function createPriceProductFilterTransfers(CartChangeTransfer $cartChangeTransfer): array
    {
        $priceProductFilterTransfers = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $priceProductFilterTransfers[] = $this->priceProductFilter->createPriceProductFilterTransfer($cartChangeTransfer, $itemTransfer);
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
            return $this->setOriginUnitNetPrice($itemTransfer, $priceProductTransfer);
        }

        return $this->setOriginUnitGrossPrice($itemTransfer, $priceProductTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function setOriginUnitGrossPrice(ItemTransfer $itemTransfer, PriceProductTransfer $priceProductTransfer): ItemTransfer
    {
        $itemTransfer->setOriginUnitNetPrice(0);
        $itemTransfer->setOriginUnitGrossPrice($priceProductTransfer->getMoneyValue()->getGrossAmount());
        $itemTransfer->setSumNetPrice(0);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function setOriginUnitNetPrice(ItemTransfer $itemTransfer, PriceProductTransfer $priceProductTransfer): ItemTransfer
    {
        $itemTransfer->setOriginUnitNetPrice($priceProductTransfer->getMoneyValue()->getNetAmount());
        $itemTransfer->setOriginUnitGrossPrice(0);
        $itemTransfer->setSumGrossPrice(0);

        return $itemTransfer;
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
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function applySourceUnitPrices(ItemTransfer $itemTransfer)
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
    protected function applyOriginUnitPrices(ItemTransfer $itemTransfer)
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
    protected function hasSourceUnitPrices(ItemTransfer $itemTransfer)
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
     * @deprecated Will be removed with a next major release
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function hasForcedUnitGrossPrice(ItemTransfer $itemTransfer)
    {
        if ($itemTransfer->getForcedUnitGrossPrice() && $itemTransfer->getUnitGrossPrice() !== null) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setQuotePriceMode(QuoteTransfer $quoteTransfer)
    {
        if (!$quoteTransfer->getPriceMode()) {
            $quoteTransfer->setPriceMode($this->priceFacade->getDefaultPriceMode());
        }

        return $quoteTransfer;
    }
}
