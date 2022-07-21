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

class BundleItemUnfolder implements BundleItemUnfolderInterface
{
    /**
     * @var string
     */
    protected const BUNDLE_IDENTIFIER_DELIMITER = '_';

    /**
     * @var string
     */
    protected const GROUP_KEY_DELIMITER = '_';

    /**
     * @var array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected static $productConcreteTransfersCache = [];

    /**
     * @var array<string>
     */
    protected static $localizedProductNameCache = [];

    /**
     * @var array<int>
     */
    protected static $productPriceCache = [];

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
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface
     */
    protected $productBundleReader;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface $productBundleReader
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Price\PriceReaderInterface $priceReader
     */
    public function __construct(
        ProductBundleToPriceProductFacadeInterface $priceProductFacade,
        ProductBundleToProductFacadeInterface $productFacade,
        ProductBundleToLocaleFacadeInterface $localeFacade,
        ProductBundleReaderInterface $productBundleReader,
        PriceReaderInterface $priceReader
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->productFacade = $productFacade;
        $this->localeFacade = $localeFacade;
        $this->productBundleReader = $productBundleReader;
        $this->priceReader = $priceReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function unfoldBundlesToUnitedItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $cartChangeItemTransfers = new ArrayObject();
        $quoteTransfer = $cartChangeTransfer->getQuoteOrFail();

        $productConcreteSkus = $this->getProductConcreteSkusFromCartChangeTransfer($cartChangeTransfer);
        $productForBundleTransfersBySku = $this->productBundleReader->getProductForBundleTransfersByProductConcreteSkus($productConcreteSkus);
        $unitedBundledChangeItemTransfersBySku = $this->getUnitedBundledChangeItemsBySku($cartChangeTransfer, $productForBundleTransfersBySku);

        /** @var array<string, bool> $bundleSkuReplaced */
        $bundleSkuReplaced = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $sku = $itemTransfer->getSkuOrFail();
            if (!isset($unitedBundledChangeItemTransfersBySku[$sku])) {
                $cartChangeItemTransfers[] = $itemTransfer;

                continue;
            }

            if (isset($bundleSkuReplaced[$sku])) {
                continue;
            }

            $bundledCartChangeItemTransfers = $this->buildBundle(
                $unitedBundledChangeItemTransfersBySku[$sku],
                $quoteTransfer,
                $productForBundleTransfersBySku[$sku],
            );

            foreach ($bundledCartChangeItemTransfers as $bundledItemTransfer) {
                $cartChangeItemTransfers->append($bundledItemTransfer);
            }

            $bundleSkuReplaced[$sku] = true;
        }

        return $cartChangeTransfer
            ->setItems($cartChangeItemTransfers)
            ->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array<string, array<\Generated\Shared\Transfer\ProductForBundleTransfer>> $productForBundleTransfersBySku
     *
     * @return array<string, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getUnitedBundledChangeItemsBySku(CartChangeTransfer $cartChangeTransfer, array $productForBundleTransfersBySku): array
    {
        /** @var array<\Generated\Shared\Transfer\ItemTransfer> $unitedBundledChangeItemTransfersBySku */
        $unitedBundledChangeItemTransfersBySku = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier()) {
                continue;
            }

            $itemTransfer->requireId();
            $sku = $itemTransfer->getSkuOrFail();

            if (!isset($productForBundleTransfersBySku[$sku])) {
                continue;
            }

            if (!isset($unitedBundledChangeItemTransfersBySku[$sku])) {
                $unitedBundledChangeItemTransfersBySku[$sku] = $itemTransfer;

                continue;
            }

            $unitedBundledChangeItemTransfersBySku[$sku]->setQuantity(
                $unitedBundledChangeItemTransfersBySku[$sku]->getQuantityOrFail() + $itemTransfer->getQuantityOrFail(),
            );
        }

        return $unitedBundledChangeItemTransfersBySku;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<\Generated\Shared\Transfer\ProductForBundleTransfer> $productForBundleTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function buildBundle(
        ItemTransfer $itemTransfer,
        QuoteTransfer $quoteTransfer,
        array $productForBundleTransfers
    ): array {
        $quantity = $itemTransfer->getQuantity();

        $productOptions = $itemTransfer->getProductOptions();
        $priceMode = $quoteTransfer->getPriceModeOrFail();

        $bundleItemTransfer = new ItemTransfer();
        $bundleItemTransfer->fromArray($itemTransfer->toArray(), true);
        $bundleItemTransfer->setQuantity($quantity);

        $bundleItemIdentifier = $this->buildBundleIdentifier($bundleItemTransfer);
        $bundleItemTransfer->setBundleItemIdentifier($bundleItemIdentifier);

        $bundleItemTransfer->setGroupKey(
            $this->buildGroupKeyWithOptions($bundleItemTransfer, $itemTransfer->getProductOptions()->getArrayCopy()),
        );

        $quoteTransfer->addBundleItem($bundleItemTransfer);

        $bundledItemTransfers = $this->createBundledItemTransfers(
            $quoteTransfer,
            $productForBundleTransfers,
            $bundleItemIdentifier,
            $quantity,
        );

        $lastBundledItemTransfer = $bundledItemTransfers[count($bundledItemTransfers) - 1];
        $lastBundledItemTransfer->setProductOptions($productOptions);

        $this->distributeBundleUnitPrice(
            $bundledItemTransfers,
            $this->getPriceByPriceMode($itemTransfer, $priceMode),
            $priceMode,
        );

        return $bundledItemTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductOptionTransfer> $productOptions
     *
     * @return array<\Generated\Shared\Transfer\ProductOptionTransfer>
     */
    protected function sortOptions(array $productOptions): array
    {
        usort(
            $productOptions,
            function (ProductOptionTransfer $productOptionLeft, ProductOptionTransfer $productOptionRight) {
                return ($productOptionLeft->getSku() < $productOptionRight->getSku()) ? -1 : 1;
            },
        );

        return $productOptions;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductOptionTransfer> $sortedProductOptions
     *
     * @return string
     */
    protected function combineProductOptionParts(array $sortedProductOptions): string
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<\Generated\Shared\Transfer\ProductForBundleTransfer> $productForBundleTransfers
     * @param string $bundleItemIdentifier
     * @param int $itemQuantity
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function createBundledItemTransfers(
        QuoteTransfer $quoteTransfer,
        array $productForBundleTransfers,
        string $bundleItemIdentifier,
        int $itemQuantity
    ): array {
        $itemTransfers = [];

        foreach ($productForBundleTransfers as $productForBundleTransfer) {
            $itemTransfers = array_merge($itemTransfers, $this->multiplyBundledItemTransfer(
                $quoteTransfer,
                $productForBundleTransfer,
                $bundleItemIdentifier,
                $itemQuantity,
                $productForBundleTransfer->getQuantity(),
            ));
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer $productForBundleTransfer
     * @param string $bundleItemIdentifier
     * @param int $itemQuantity
     * @param int $multiplyTimes
     *
     * @return array
     */
    protected function multiplyBundledItemTransfer(
        QuoteTransfer $quoteTransfer,
        ProductForBundleTransfer $productForBundleTransfer,
        string $bundleItemIdentifier,
        int $itemQuantity,
        int $multiplyTimes
    ): array {
        $itemTransfers = [];

        for ($i = 0; $i < $multiplyTimes; $i++) {
            $itemTransfers[] = $this->createBundledItemTransfer(
                $productForBundleTransfer,
                $quoteTransfer,
                $bundleItemIdentifier,
                $itemQuantity,
            );
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function buildBundleIdentifier(ItemTransfer $itemTransfer): string
    {
        return $this->buildGroupKey($itemTransfer) . static::BUNDLE_IDENTIFIER_DELIMITER . uniqid('1');
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $bundledItemTransfers
     * @param int $bundleUnitPrice
     * @param string $priceMode
     *
     * @throws \OutOfBoundsException
     *
     * @return void
     */
    protected function distributeBundleUnitPrice(array $bundledItemTransfers, int $bundleUnitPrice, string $priceMode): void
    {
        $totalBundledItemUnitGrossPrice = $this->calculateBundleTotalUnitPrice($bundledItemTransfers, $priceMode);

        if ($totalBundledItemUnitGrossPrice <= 0) {
            return;
        }

        $roundingError = 0;
        $priceRatio = $bundleUnitPrice / $totalBundledItemUnitGrossPrice;

        foreach ($bundledItemTransfers as $itemTransfer) {
            $unitPrice = $this->getPriceByPriceMode($itemTransfer, $priceMode);
            if ($unitPrice <= 0) {
                throw new OutOfBoundsException('Invalid price given, natural integer expected.');
            }

            $priceBeforeRound = ($unitPrice * $priceRatio) + $roundingError;
            $priceRounded = (int)round($priceBeforeRound);
            $roundingError = $priceBeforeRound - $priceRounded;

            $this->setPrice($itemTransfer, $priceRounded, $priceMode);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer $productForBundleTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $bundleItemIdentifier
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createBundledItemTransfer(
        ProductForBundleTransfer $productForBundleTransfer,
        QuoteTransfer $quoteTransfer,
        string $bundleItemIdentifier,
        int $quantity
    ) {
        $productConcreteTransfer = $this->getProductConcreteTransfer(
            $productForBundleTransfer->getSku(),
        );

        $localizedProductName = $this->getLocalizedProductName(
            $productConcreteTransfer,
            $this->localeFacade->getCurrentLocale(),
        );

        $unitPrice = $this->getProductPrice(
            $quoteTransfer,
            $productForBundleTransfer->getSku(),
        );

        $itemTransfer = (new ItemTransfer())
            ->setId($productConcreteTransfer->getIdProductConcrete())
            ->setSku($productConcreteTransfer->getSku())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setAbstractSku($productConcreteTransfer->getAbstractSku())
            ->setName($localizedProductName)
            ->setQuantity($quantity)
            ->setRelatedBundleItemIdentifier($bundleItemIdentifier)
            ->setConcreteAttributes($productConcreteTransfer->getAttributes());

        $this->setPrice($itemTransfer, $unitPrice, $quoteTransfer->getPriceMode());

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     *
     * @return int
     */
    protected function getProductPrice(QuoteTransfer $quoteTransfer, string $sku): int
    {
        if (!isset(static::$productPriceCache[$sku])) {
            $priceFilterTransfer = $this->createStoreSpecificPriceProductFilterTransfer($quoteTransfer, $sku);
            static::$productPriceCache[$sku] = $this->priceProductFacade->findPriceFor($priceFilterTransfer);
        }

        return static::$productPriceCache[$sku];
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function getProductConcreteTransfer(string $sku): ProductConcreteTransfer
    {
        if (!isset(static::$productConcreteTransfersCache[$sku])) {
            static::$productConcreteTransfersCache[$sku] = $this->productFacade->getProductConcrete($sku);
        }

        return static::$productConcreteTransfersCache[$sku];
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
    ): string {
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
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $bundledItemTransfers
     * @param string $priceMode
     *
     * @return int
     */
    protected function calculateBundleTotalUnitPrice(array $bundledItemTransfers, string $priceMode): int
    {
        $totalBundleItemAmount = 0;

        foreach ($bundledItemTransfers as $bundledItemTransfer) {
            if ($priceMode === $this->priceReader->getNetPriceModeIdentifier()) {
                $totalBundleItemAmount += $bundledItemTransfer->getUnitNetPriceOrFail();

                continue;
            }

            $totalBundleItemAmount += $bundledItemTransfer->getUnitGrossPriceOrFail();
        }

        return $totalBundleItemAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param array<\Generated\Shared\Transfer\ProductOptionTransfer> $productOptionTransfers
     *
     * @return string
     */
    protected function buildGroupKeyWithOptions(ItemTransfer $bundleItemTransfer, array $productOptionTransfers): string
    {
        if (count($productOptionTransfers) === 0) {
            return $this->buildGroupKey($bundleItemTransfer);
        }

        $productOptionTransfers = $this->sortOptions($productOptionTransfers);

        return $this->buildGroupKey($bundleItemTransfer) . static::GROUP_KEY_DELIMITER . $this->combineProductOptionParts($productOptionTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function buildGroupKey(ItemTransfer $itemTransfer): string
    {
        if ($itemTransfer->getGroupKeyPrefix()) {
            return $itemTransfer->getGroupKeyPrefix() . static::GROUP_KEY_DELIMITER . $itemTransfer->getSkuOrFail();
        }

        return $itemTransfer->getSkuOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $unitPrice
     * @param string $priceMode
     *
     * @return void
     */
    protected function setPrice(ItemTransfer $itemTransfer, int $unitPrice, string $priceMode): void
    {
        if ($priceMode === $this->priceReader->getNetPriceModeIdentifier()) {
            $itemTransfer
                ->setUnitNetPrice($unitPrice)
                ->setUnitGrossPrice(0)
                ->setSumGrossPrice(0);

            return;
        }

        $itemTransfer
            ->setUnitGrossPrice($unitPrice)
            ->setUnitNetPrice(0)
            ->setSumNetPrice(0);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     *
     * @return int
     */
    protected function getPriceByPriceMode(ItemTransfer $itemTransfer, string $priceMode): int
    {
        if ($priceMode === $this->priceReader->getNetPriceModeIdentifier()) {
            return $itemTransfer->getUnitNetPriceOrFail();
        }

        return $itemTransfer->getUnitGrossPriceOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function createStoreSpecificPriceProductFilterTransfer(QuoteTransfer $quoteTransfer, string $sku): PriceProductFilterTransfer
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
}
