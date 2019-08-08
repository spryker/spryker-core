<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToMessengerInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface;

class ItemsWithoutPriceFilter implements ItemFilterInterface
{
    protected const MESSAGE_PARAM_SKU = '%sku%';
    protected const MESSAGE_INFO_CONCRETE_PRODUCT_WITHOUT_PRICE_REMOVED = 'price-cart-connector.info.concrete-product-without-price.removed';

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface $priceFacade
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface $priceProductFacade
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToMessengerInterface $messengerFacade
     */
    public function __construct(
        PriceCartToPriceInterface $priceFacade,
        PriceCartToPriceProductInterface $priceProductFacade,
        PriceCartToMessengerInterface $messengerFacade
    ) {
        $this->priceFacade = $priceFacade;
        $this->priceProductFacade = $priceProductFacade;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $priceProductFilters = $this->createPriceProductFilters($quoteTransfer);
        $validPrices = $this->priceProductFacade->getValidPrices($priceProductFilters);

        $productWithoutProductSkus = $this->getProductWithoutPriceSkus($validPrices, $quoteTransfer->getItems()->getArrayCopy());

        return $this->removeItemsWithoutPrice($quoteTransfer, $productWithoutProductSkus);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string[] $productWithoutPricesSkus
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function removeItemsWithoutPrice(QuoteTransfer $quoteTransfer, array $productWithoutPricesSkus): QuoteTransfer
    {
        foreach ($productWithoutPricesSkus as $sku) {
            $key = $this->findItemKeyBySku($quoteTransfer->getItems(), $sku);
            if ($key !== null) {
                $this->addFilterMessage($sku);
                $quoteTransfer->getItems()->offsetUnset($key);
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param string $sku
     *
     * @return int|null
     */
    protected function findItemKeyBySku(ArrayObject $itemTransfers, string $sku): ?int
    {
        foreach ($itemTransfers as $key => $itemTransfer) {
            if ($itemTransfer->getSku() === $sku) {
                return (int)$key;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function createPriceProductFilter(
        ItemTransfer $itemTransfer,
        QuoteTransfer $quoteTransfer
    ): PriceProductFilterTransfer {
        $priceMode = $this->getPriceMode($quoteTransfer);
        $currencyTransfer = $quoteTransfer->getCurrency();
        $storeName = $this->findStoreName($quoteTransfer);

        $priceProductFilterTransfer = $this->mapItemTransferToPriceProductFilterTransfer(
            (new PriceProductFilterTransfer()),
            $itemTransfer
        );

        $priceProductFilterTransfer->setStoreName($storeName)
            ->setPriceMode($priceMode)
            ->setCurrencyIsoCode($currencyTransfer->getCode())
            ->setPriceTypeName($this->priceProductFacade->getDefaultPriceTypeName());

        if ($this->isPriceProductDimensionEnabled($priceProductFilterTransfer)) {
            $priceProductFilterTransfer->setQuote($quoteTransfer);
        }

        return $priceProductFilterTransfer;
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function addFilterMessage(string $sku): void
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue(static::MESSAGE_INFO_CONCRETE_PRODUCT_WITHOUT_PRICE_REMOVED);
        $messageTransfer->setParameters([
            static::MESSAGE_PARAM_SKU => $sku,
        ]);

        $this->messengerFacade->addInfoMessage($messageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function mapItemTransferToPriceProductFilterTransfer(
        PriceProductFilterTransfer $priceProductFilterTransfer,
        ItemTransfer $itemTransfer
    ): PriceProductFilterTransfer {
        $priceProductFilterTransfer->fromArray($itemTransfer->toArray(), true);

        return $priceProductFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getPriceMode(QuoteTransfer $quoteTransfer): string
    {
        if (!$quoteTransfer->getPriceMode()) {
            return $this->priceFacade->getDefaultPriceMode();
        }

        return $quoteTransfer->getPriceMode();
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
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer[]
     */
    protected function createPriceProductFilters(QuoteTransfer $quoteTransfer): array
    {
        $priceProductFilters = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $priceProductFilters[] = $this->createPriceProductFilter($itemTransfer, $quoteTransfer);
        }

        return $priceProductFilters;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return string[]
     */
    protected function getProductWithoutPriceSkus(array $priceProductTransfers, array $items): array
    {
        $itemSkus = array_map(function (ItemTransfer $item) {
            return $item->getSku();
        }, $items);

        $validProductSkus = array_map(function (PriceProductTransfer $priceProductTransfer) {
            return $priceProductTransfer->getSkuProduct();
        }, $priceProductTransfers);

        return array_diff($itemSkus, $validProductSkus);
    }
}
