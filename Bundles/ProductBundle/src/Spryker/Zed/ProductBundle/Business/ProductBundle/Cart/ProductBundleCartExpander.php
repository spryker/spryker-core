<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Cart;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductConcreteConditionsTransfer;
use Generated\Shared\Transfer\ProductConcreteCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use OutOfBoundsException;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Price\PriceReaderInterface;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceProductFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductFacadeInterface;
use Spryker\Zed\ProductBundle\Exception\MissingFacadeMethodException;
use Spryker\Zed\ProductBundle\ProductBundleConfig;

class ProductBundleCartExpander implements ProductBundleCartExpanderInterface
{
    /**
     * @var string
     */
    public const BUNDLE_IDENTIFIER_DELIMITER = '_';

    /**
     * @var string
     */
    protected const GROUP_KEY_DELIMITER = '_';

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundle\Price\PriceReaderInterface
     */
    protected $priceReader;

    /**
     * @var array
     */
    protected static $productConcreteCache = [];

    /**
     * @var array
     */
    protected static $localizedProductNameCache = [];

    /**
     * @var array
     */
    protected static $productPriceCache = [];

    /**
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface
     */
    protected $productBundleReader;

    /**
     * @var \Spryker\Zed\ProductBundle\ProductBundleConfig
     */
    protected ProductBundleConfig $productBundleConfig;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface $productBundleReader
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Price\PriceReaderInterface $priceReader
     * @param \Spryker\Zed\ProductBundle\ProductBundleConfig $productBundleConfig
     */
    public function __construct(
        ProductBundleToPriceProductFacadeInterface $priceProductFacade,
        ProductBundleToProductFacadeInterface $productFacade,
        ProductBundleToLocaleFacadeInterface $localeFacade,
        ProductBundleReaderInterface $productBundleReader,
        PriceReaderInterface $priceReader,
        ProductBundleConfig $productBundleConfig
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->productFacade = $productFacade;
        $this->localeFacade = $localeFacade;
        $this->productBundleReader = $productBundleReader;
        $this->priceReader = $priceReader;
        $this->productBundleConfig = $productBundleConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandBundleItems(CartChangeTransfer $cartChangeTransfer)
    {
        $cartChangeTransfer->requireQuote();

        $cartChangeItems = new ArrayObject();
        $quoteTransfer = $cartChangeTransfer->getQuote();

        $productConcreteSkus = $this->getProductConcreteSkusFromCartChangeTransfer($cartChangeTransfer);
        $productForBundleTransfers = $this->productBundleReader->getProductForBundleTransfersByProductConcreteSkus($productConcreteSkus);

        $productConcreteSkus = [];
        foreach ($productForBundleTransfers as $productForBundleTransfersBySku) {
            foreach ($productForBundleTransfersBySku as $productForBundleTransfer) {
                $productConcreteSkus[] = $productForBundleTransfer->getSku();
            }
        }
        $this->preloadProductConcreteTransfers($productConcreteSkus);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier()) {
                $cartChangeItems->append($itemTransfer);

                continue;
            }

            $itemTransfer->requireId()->requireQuantity();
            $this->requirePriceByMode($itemTransfer, $quoteTransfer->getPriceMode());

            $productForBundleTransfersBySku = $productForBundleTransfers[$itemTransfer->getSku()] ?? null;

            if (!$productForBundleTransfersBySku) {
                $cartChangeItems->append($itemTransfer);

                continue;
            }

            $addToCartItems = $this->buildBundle($itemTransfer, $quoteTransfer, $productForBundleTransfersBySku);

            foreach ($addToCartItems as $bundledItemTransfer) {
                $cartChangeItems->append($bundledItemTransfer);
            }
        }

        $cartChangeTransfer->setItems($cartChangeItems);
        $cartChangeTransfer->setQuote($quoteTransfer);

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<\Generated\Shared\Transfer\ProductForBundleTransfer> $productForBundleTransfers
     *
     * @return array
     */
    protected function buildBundle(
        ItemTransfer $itemTransfer,
        QuoteTransfer $quoteTransfer,
        array $productForBundleTransfers
    ) {
        $addToCartItems = [];
        $quantity = $itemTransfer->getQuantity();

        $productOptions = $itemTransfer->getProductOptions();
        $priceMode = $quoteTransfer->getPriceMode();

        for ($i = 0; $i < $quantity; $i++) {
            $bundleItemTransfer = new ItemTransfer();
            $bundleItemTransfer->fromArray($itemTransfer->toArray(), true);
            $bundleItemTransfer->setQuantity(1);

            $bundleItemIdentifier = $this->buildBundleIdentifier($bundleItemTransfer);
            $bundleItemTransfer->setBundleItemIdentifier($bundleItemIdentifier);

            $this->setGroupKey($itemTransfer, $bundleItemTransfer);

            $quoteTransfer->addBundleItem($bundleItemTransfer);

            $bundledItems = $this->createBundledItemsTransferCollection(
                $productForBundleTransfers,
                $bundleItemIdentifier,
                $quoteTransfer,
            );

            $bundledItems = $this->copyAdditionalBundleItemFields($bundledItems, $bundleItemTransfer);

            $lastBundledItemTransfer = $bundledItems[count($bundledItems) - 1];
            $lastBundledItemTransfer->setProductOptions($productOptions);

            $this->distributeBundleUnitPrice(
                $bundledItems,
                $this->getPriceByPriceMode($itemTransfer, $priceMode),
                $quoteTransfer->getPriceMode(),
            );

            $addToCartItems = array_merge($addToCartItems, $bundledItems);
        }

        return $addToCartItems;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return array
     */
    protected function sortOptions(array $options)
    {
        usort(
            $options,
            function (ProductOptionTransfer $productOptionLeft, ProductOptionTransfer $productOptionRight) {
                return ($productOptionLeft->getSku() < $productOptionRight->getSku()) ? -1 : 1;
            },
        );

        return $options;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductOptionTransfer> $sortedProductOptions
     *
     * @return string
     */
    protected function combineOptionParts(array $sortedProductOptions)
    {
        $groupKeyPart = [];
        foreach ($sortedProductOptions as $productOptionTransfer) {
            if (!$productOptionTransfer->getSku()) {
                continue;
            }
            $groupKeyPart[] = $productOptionTransfer->getSku();
        }

        return implode('_', $groupKeyPart);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductForBundleTransfer> $productForBundleTransfers
     * @param string $bundleItemIdentifier
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function createBundledItemsTransferCollection(array $productForBundleTransfers, $bundleItemIdentifier, QuoteTransfer $quoteTransfer)
    {
        $productConcreteSkus = [];
        foreach ($productForBundleTransfers as $productForBundleTransfer) {
            $productConcreteSkus[] = $productForBundleTransfer->getSku();
        }

        $this->preloadProductConcreteTransfers($productConcreteSkus);

        $bundledItems = [];
        foreach ($productForBundleTransfers as $productForBundleTransfer) {
            $quantity = $productForBundleTransfer->getQuantity();
            $bundledItemTransfer = $this->createBundledItemTransfer(
                $productForBundleTransfer,
                $bundleItemIdentifier,
                $quoteTransfer,
            );
            for ($i = 0; $i < $quantity; $i++) {
                $bundledItems[] = clone $bundledItemTransfer;
            }
        }

        return $bundledItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function buildBundleIdentifier(ItemTransfer $itemTransfer)
    {
        $itemTransfer->requireSku();

        return $this->buildGroupKey($itemTransfer) . static::BUNDLE_IDENTIFIER_DELIMITER . uniqid('1');
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $bundledProducts
     * @param int $bundleUnitPrice
     * @param string $priceMode
     *
     * @throws \OutOfBoundsException
     *
     * @return void
     */
    protected function distributeBundleUnitPrice(array $bundledProducts, $bundleUnitPrice, $priceMode)
    {
        $totalBundledItemUnitGrossPrice = $this->calculateBundleTotalUnitPrice($bundledProducts, $priceMode);
        if ($totalBundledItemUnitGrossPrice <= 0) {
            return;
        }

        $roundingError = 0;
        $priceRatio = $bundleUnitPrice / $totalBundledItemUnitGrossPrice;
        foreach ($bundledProducts as $itemTransfer) {
            $this->requirePriceByMode($itemTransfer, $priceMode);

            $unitPrice = $this->getPriceByPriceMode($itemTransfer, $priceMode);
            if ($unitPrice < 0) {
                throw new OutOfBoundsException('Invalid price given, non-negative integer expected.');
            }

            $priceBeforeRound = ($unitPrice * $priceRatio) + $roundingError;
            $priceRounded = (int)round($priceBeforeRound);
            $roundingError = $priceBeforeRound - $priceRounded;

            $this->setPrice($itemTransfer, $priceRounded, $priceMode);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer $productForBundleTransfer
     * @param string $bundleItemIdentifier
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createBundledItemTransfer(
        ProductForBundleTransfer $productForBundleTransfer,
        $bundleItemIdentifier,
        QuoteTransfer $quoteTransfer
    ) {
        $productConcreteTransfer = $this->getProductConcreteTransfer(
            $productForBundleTransfer->getSku(),
        );

        $localizedProductName = $this->getLocalizedProductName(
            $productConcreteTransfer,
            $this->localeFacade->getCurrentLocale(),
        );

        $unitPrice = $this->getProductPrice(
            $productForBundleTransfer->getSku(),
            $quoteTransfer,
        );

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setId($productConcreteTransfer->getIdProductConcrete())
            ->setSku($productConcreteTransfer->getSku())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setAbstractSku($productConcreteTransfer->getAbstractSku())
            ->setName($localizedProductName)
            ->setQuantity(1)
            ->setRelatedBundleItemIdentifier($bundleItemIdentifier)
            ->setConcreteAttributes($productConcreteTransfer->getAttributes());

        $this->setPrice($itemTransfer, $unitPrice, $quoteTransfer->getPriceMode());

        return $itemTransfer;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getProductPrice($sku, QuoteTransfer $quoteTransfer)
    {
        if (!isset(static::$productPriceCache[$sku])) {
            $priceFilterTransfer = $this->createStoreSpecificPriceProductFilterTransfer($sku, $quoteTransfer);
            static::$productPriceCache[$sku] = $this->priceProductFacade->findPriceFor($priceFilterTransfer);
        }

        return static::$productPriceCache[$sku];
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function getProductConcreteTransfer($sku)
    {
        if (!isset(static::$productConcreteCache[$sku])) {
            static::$productConcreteCache[$sku] = $this->productFacade->getProductConcrete($sku);
        }

        return static::$productConcreteCache[$sku];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $currentLocaleTransfer
     *
     * @return string
     */
    protected function getLocalizedProductName(
        ProductConcreteTransfer $productConcreteTransfer,
        LocaleTransfer $currentLocaleTransfer
    ) {
        $localeMapKey = $currentLocaleTransfer->getLocaleName() . $productConcreteTransfer->getIdProductConcrete();

        if (!isset(static::$localizedProductNameCache[$localeMapKey])) {
            static::$localizedProductNameCache[$localeMapKey] = $this->productFacade->getLocalizedProductConcreteName(
                $productConcreteTransfer,
                $this->localeFacade->getCurrentLocale(),
            );
        }

        return static::$localizedProductNameCache[$localeMapKey];
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $bundledProducts
     * @param string $priceMode
     *
     * @return int
     */
    protected function calculateBundleTotalUnitPrice(array $bundledProducts, $priceMode)
    {
        $totalBundleItemAmount = (int)array_reduce($bundledProducts, function ($total, ItemTransfer $itemTransfer) use ($priceMode) {
            if ($priceMode === $this->priceReader->getNetPriceModeIdentifier()) {
                $total += $itemTransfer->getUnitNetPrice();

                return $total;
            }

            $total += $itemTransfer->getUnitGrossPrice();

            return $total;
        });

        return $totalBundleItemAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     *
     * @return void
     */
    protected function setGroupKey(ItemTransfer $itemTransfer, ItemTransfer $bundleItemTransfer)
    {
        $options = (array)$itemTransfer->getProductOptions();
        if (count($options) === 0) {
            $bundleItemTransfer->setGroupKey($this->buildGroupKey($bundleItemTransfer));

            return;
        }

        $options = $this->sortOptions($options);
        $groupKey = $this->buildGroupKey($bundleItemTransfer) . static::GROUP_KEY_DELIMITER . $this->combineOptionParts($options);
        $bundleItemTransfer->setGroupKey($groupKey);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function buildGroupKey(ItemTransfer $itemTransfer): string
    {
        if ($itemTransfer->getGroupKeyPrefix()) {
            return $itemTransfer->getGroupKeyPrefix() . static::GROUP_KEY_DELIMITER . $itemTransfer->getSku();
        }

        return $itemTransfer->getSku();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int|null $unitPrice
     * @param string $priceMode
     *
     * @return void
     */
    protected function setPrice(ItemTransfer $itemTransfer, $unitPrice, $priceMode)
    {
        if ($unitPrice === null) {
            $unitPrice = 0;
        }

        if ($priceMode === $this->priceReader->getNetPriceModeIdentifier()) {
            $itemTransfer->setUnitNetPrice($unitPrice);
            $itemTransfer->setUnitGrossPrice(0);
            $itemTransfer->setSumGrossPrice(0);

            return;
        }

        $itemTransfer->setUnitGrossPrice($unitPrice);
        $itemTransfer->setUnitNetPrice(0);
        $itemTransfer->setSumNetPrice(0);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     *
     * @return void
     */
    protected function requirePriceByMode(ItemTransfer $itemTransfer, $priceMode)
    {
        if ($priceMode === $this->priceReader->getNetPriceModeIdentifier()) {
            $itemTransfer->requireUnitNetPrice();

            return;
        }

        $itemTransfer->requireUnitGrossPrice();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     *
     * @return int
     */
    protected function getPriceByPriceMode(ItemTransfer $itemTransfer, $priceMode)
    {
        if ($priceMode === $this->priceReader->getNetPriceModeIdentifier()) {
            return $itemTransfer->getUnitNetPrice();
        }

        return $itemTransfer->getUnitGrossPrice();
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function createStoreSpecificPriceProductFilterTransfer(string $sku, QuoteTransfer $quoteTransfer): PriceProductFilterTransfer
    {
        return (new PriceProductFilterTransfer())
            ->setSku($sku)
            ->setCurrencyIsoCode($quoteTransfer->getCurrency()->getCode())
            ->setPriceMode($quoteTransfer->getPriceMode())
            ->setStoreName($quoteTransfer->getStore()->getName());
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<string>
     */
    protected function getProductConcreteSkusFromCartChangeTransfer(CartChangeTransfer $cartChangeTransfer): array
    {
        $productConcreteSkus = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $productConcreteSkus[] = $itemTransfer->getSku();
        }

        return $productConcreteSkus;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $bundledItems
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function copyAdditionalBundleItemFields(array $bundledItems, ItemTransfer $bundleItemTransfer): array
    {
        foreach ($bundledItems as $bundledItem) {
            foreach ($this->productBundleConfig->getAllowedBundleItemFieldsToCopy() as $allowedFieldToCopy) {
                $bundledItem->offsetSet($allowedFieldToCopy, $bundleItemTransfer->offsetGet($allowedFieldToCopy));
            }
        }

        return $bundledItems;
    }

    /**
     * @param array<string> $productConcreteSkus
     *
     * @return void
     */
    protected function preloadProductConcreteTransfers(array $productConcreteSkus): void
    {
        if (!$productConcreteSkus) {
            return;
        }

        $skusToLoad = array_filter($productConcreteSkus, function ($sku) {
            return !isset(static::$productConcreteCache[$sku]);
        });

        if (!$skusToLoad) {
            return;
        }

        $productConcreteCriteriaTransfer = (new ProductConcreteCriteriaTransfer())->setProductConcreteConditions(
            (new ProductConcreteConditionsTransfer())->setSkus($skusToLoad),
        );

        // This check is needed for BC reasons, because the method was added in spryker/product:6.35.0.
        try {
            $productConcreteCollectionTransfers = $this->productFacade->getProductConcreteCollection($productConcreteCriteriaTransfer);
            $productConcreteTransfers = $productConcreteCollectionTransfers->getProducts();
        } catch (MissingFacadeMethodException $e) {
            return;
        }

        if (!$productConcreteTransfers) { //@phpstan-ignore-line It can be null (at least in tests), but PHPStan relies on the transfer annotations
            return;
        }
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            static::$productConcreteCache[$productConcreteTransfer->getSku()] = $productConcreteTransfer;
        }
    }
}
