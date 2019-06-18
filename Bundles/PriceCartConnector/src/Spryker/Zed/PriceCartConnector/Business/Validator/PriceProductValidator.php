<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig;

class PriceProductValidator implements PriceProductValidatorInterface
{
    public const CART_PRE_CHECK_PRICE_FAILED_TRANSLATION_KEY = 'cart.pre.check.price.failed';
    public const CART_PRE_CHECK_MIN_PRICE_RESTRICTION_FAILED_KEY = 'cart.pre.check.min_price.failed';

    protected const MESSAGE_GLOSSARY_KEY_PRICE = '%price%';
    protected const MESSAGE_GLOSSARY_KEY_CURRENCY_ISO_CODE = '%currencyIsoCode%';

    /**
     * @var string
     */
    protected static $netPriceModeIdentifier;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig
     */
    protected $config;

    /**
     * @var string|null
     */
    protected $defaultPriceTypeName;

    /**
     * @var string|null
     */
    protected $defaultPriceMode;

    /**
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface $priceProductFacade
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface $priceFacade
     * @param \Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig $config
     */
    public function __construct(
        PriceCartToPriceProductInterface $priceProductFacade,
        PriceCartToPriceInterface $priceFacade,
        PriceCartConnectorConfig $config
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->priceFacade = $priceFacade;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validatePrices(CartChangeTransfer $cartChangeTransfer)
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())
            ->setIsSuccess(true);
        $priceProductFilters = $this->createPriceProductFilters($cartChangeTransfer->getItems(), $cartChangeTransfer->getQuote());
        $validPriceProductTransfers = $this->priceProductFacade->getValidPrices($priceProductFilters);

        $cartPreCheckResponseTransfer = $this->checkMinPriceRestriction($cartChangeTransfer, $validPriceProductTransfers, $cartPreCheckResponseTransfer);
        if (!$cartPreCheckResponseTransfer->getIsSuccess()) {
            return $cartPreCheckResponseTransfer;
        }

        return $this->checkProductWithoutPricesRestriction($validPriceProductTransfers, $cartChangeTransfer, $cartPreCheckResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $validPriceProductTransfers
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function checkProductWithoutPricesRestriction(
        array $validPriceProductTransfers,
        CartChangeTransfer $cartChangeTransfer,
        CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
    ): CartPreCheckResponseTransfer {
        $productWithoutPriceSkus = $this->getProductWithoutPriceSkus($validPriceProductTransfers, $cartChangeTransfer->getItems()->getArrayCopy());
        if ($productWithoutPriceSkus) {
            $sku = array_shift($productWithoutPriceSkus);

            return $cartPreCheckResponseTransfer
                ->setIsSuccess(false)
                ->addMessage($this->createMessage($sku));
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $validPriceProductTransfers
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function checkMinPriceRestriction(
        CartChangeTransfer $cartChangeTransfer,
        array $validPriceProductTransfers,
        CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
    ): CartPreCheckResponseTransfer {
        $indexedValidPriceProductTransfers = $this->indexPriceProductTransfersBySku($validPriceProductTransfers);
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $priceProductTransfer = $indexedValidPriceProductTransfers[$itemTransfer->getSku()] ?? null;
            if (!$priceProductTransfer) {
                continue;
            }
            $price = $itemTransfer->getQuantity() * (int)$this->getPriceFromPriceProductTransfer($priceProductTransfer);
            if ($price < $this->config->getMinPriceRestriction()) {
                return $cartPreCheckResponseTransfer
                    ->setIsSuccess(false)
                    ->addMessage($this->createMessageMinPriceRestriction($cartChangeTransfer->getQuote()->getCurrency()->getCode()));
            }
        }

        return $cartPreCheckResponseTransfer;
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
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return int|null
     */
    protected function getPriceFromPriceProductTransfer(PriceProductTransfer $priceProductTransfer): ?int
    {
        if ($priceProductTransfer->getPriceTypeName() === $this->getNetPriceModeIdentifier()) {
            return $priceProductTransfer->getMoneyValue()->getNetAmount();
        }

        return $priceProductTransfer->getMoneyValue()->getGrossAmount();
    }

    /**
     * @return string
     */
    public function getNetPriceModeIdentifier(): string
    {
        if (!static::$netPriceModeIdentifier) {
            static::$netPriceModeIdentifier = $this->priceFacade->getNetPriceModeIdentifier();
        }

        return static::$netPriceModeIdentifier;
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageMinPriceRestriction(string $currencyIsoCode): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue(static::CART_PRE_CHECK_MIN_PRICE_RESTRICTION_FAILED_KEY)
            ->setParameters([
                static::MESSAGE_GLOSSARY_KEY_PRICE => $this->config->getMinPriceRestriction(),
                static::MESSAGE_GLOSSARY_KEY_CURRENCY_ISO_CODE => $currencyIsoCode,
            ]);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function createPriceProductFilter(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): PriceProductFilterTransfer
    {
        $priceMode = $this->getPriceMode($quoteTransfer);
        $currencyTransfer = $quoteTransfer->getCurrency();
        $storeName = $this->findStoreName($quoteTransfer);

        $priceProductFilterTransfer = $this->mapItemTransferToPriceProductFilterTransfer(
            (new PriceProductFilterTransfer()),
            $itemTransfer
        )
            ->setStoreName($storeName)
            ->setPriceMode($priceMode)
            ->setCurrencyIsoCode($currencyTransfer->getCode())
            ->setPriceTypeName($this->priceProductFacade->getDefaultPriceTypeName());

        if ($this->isPriceProductDimensionEnabled($priceProductFilterTransfer)) {
            $priceProductFilterTransfer->setQuote($quoteTransfer);
        }

        return $priceProductFilterTransfer;
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
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessage(string $sku): MessageTransfer
    {
        return (new MessageTransfer())
         ->setValue(static::CART_PRE_CHECK_PRICE_FAILED_TRANSLATION_KEY)
         ->setParameters(['%sku%' => $sku]);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getPriceMode(QuoteTransfer $quoteTransfer): string
    {
        if (!$quoteTransfer->getPriceMode()) {
            return $this->getDefaultPriceMode();
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
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return string[]
     */
    protected function getProductWithoutPriceSkus(array $priceProductTransfers, array $items): array
    {
        $totalSkus = array_map(function (ItemTransfer $item) {
            return $item->getSku();
        }, $items);

        $validSkus = array_map(function (PriceProductTransfer $priceProductTransfer) {
            return $priceProductTransfer->getSkuProduct();
        }, $priceProductTransfers);

        return array_diff($totalSkus, $validSkus);
    }

    /**
     * @return string
     */
    protected function getDefaultPriceMode(): string
    {
        if ($this->defaultPriceMode === null) {
            $this->defaultPriceMode = $this->priceFacade->getDefaultPriceMode();
        }

        return $this->defaultPriceMode;
    }

    /**
     * @return string
     */
    protected function getDefaultPriceTypeName(): string
    {
        if ($this->defaultPriceTypeName === null) {
            $this->defaultPriceTypeName = $this->priceProductFacade->getDefaultPriceTypeName();
        }

        return $this->defaultPriceTypeName;
    }

    /**
     * @param \ArrayObject $itemTransfers
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer[]
     */
    protected function createPriceProductFilters(ArrayObject $itemTransfers, QuoteTransfer $quoteTransfer): array
    {
        $priceProductFilters = [];
        foreach ($itemTransfers as $itemTransfer) {
            $priceProductFilters[] = $this->createPriceProductFilter($itemTransfer, $quoteTransfer);
        }

        return $priceProductFilters;
    }
}
