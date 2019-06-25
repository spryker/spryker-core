<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\Validator;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig;

class PriceProductValidator implements PriceProductValidatorInterface
{
    public const CART_PRE_CHECK_PRICE_FAILED_TRANSLATION_KEY = 'cart.pre.check.price.failed';
    public const CART_PRE_CHECK_MIN_PRICE_RESTRICTION_FAILED_KEY = 'cart.pre.check.min_price.failed';

    protected const MESSAGE_GLOSSARY_KEY_PRICE = '%price%';
    protected const MESSAGE_GLOSSARY_KEY_CURRENCY_ISO_CODE = '%currencyIsoCode%';

    protected const CENT_REPRESENTATION_DIVIDER = 100;

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
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface $priceProductFacade
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface $priceFacade
     * @param \Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig $config
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        PriceCartToPriceProductInterface $priceProductFacade,
        PriceCartToPriceInterface $priceFacade,
        PriceCartConnectorConfig $config,
        PriceCartConnectorToCurrencyFacadeInterface $currencyFacade
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->priceFacade = $priceFacade;
        $this->config = $config;
        $this->currencyFacade = $currencyFacade;
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

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $priceProductFilterTransfer = $this->createPriceProductFilter($itemTransfer, $cartChangeTransfer->getQuote());

            if ($this->priceProductFacade->hasValidPriceFor($priceProductFilterTransfer)) {
                $cartPreCheckResponseTransfer = $this->checkMinPriceRestriction(
                    $cartPreCheckResponseTransfer,
                    $itemTransfer,
                    $priceProductFilterTransfer
                );

                if ($cartPreCheckResponseTransfer->getIsSuccess()) {
                    continue;
                }

                return $cartPreCheckResponseTransfer;
            }

            return $cartPreCheckResponseTransfer
                ->setIsSuccess(false)
                ->addMessage($this->createMessage($itemTransfer));
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function checkMinPriceRestriction(
        CartPreCheckResponseTransfer $cartPreCheckResponseTransfer,
        ItemTransfer $itemTransfer,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): CartPreCheckResponseTransfer {
        $price = $this->priceProductFacade->findPriceBySku($itemTransfer->getSku(), $priceProductFilterTransfer->getPriceTypeName());
        $sumPrice = $itemTransfer->getQuantity() * $price;

        if ($sumPrice < $this->config->getMinPriceRestriction()) {
            return $cartPreCheckResponseTransfer
                ->setIsSuccess(false)
                ->addMessage($this->createMessageMinPriceRestriction($priceProductFilterTransfer->getCurrencyIsoCode()));
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageMinPriceRestriction(string $currencyIsoCode): MessageTransfer
    {
        $parameterMinPrice = $this->config->getMinPriceRestriction() / static::CENT_REPRESENTATION_DIVIDER;

        return (new MessageTransfer())
            ->setValue(static::CART_PRE_CHECK_MIN_PRICE_RESTRICTION_FAILED_KEY)
            ->setParameters([
                static::MESSAGE_GLOSSARY_KEY_PRICE => $parameterMinPrice,
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
        $currencyCode = $this->getCurrencyCode($quoteTransfer);
        $storeName = $this->findStoreName($quoteTransfer);

        $itemInQuote = $this->getItemInQuote($itemTransfer, $quoteTransfer);
        $priceProductFilterTransfer = $this->mapItemTransferToPriceProductFilterTransfer(
            (new PriceProductFilterTransfer()),
            $itemTransfer,
            $itemInQuote
        )
            ->setStoreName($storeName)
            ->setPriceMode($priceMode)
            ->setCurrencyIsoCode($currencyCode)
            ->setPriceTypeName($this->priceProductFacade->getDefaultPriceTypeName());

        if ($this->isPriceProductDimensionEnabled($priceProductFilterTransfer)) {
            $priceProductFilterTransfer->setQuote($quoteTransfer);
        }

        return $priceProductFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer|null $itemInQuote
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function mapItemTransferToPriceProductFilterTransfer(
        PriceProductFilterTransfer $priceProductFilterTransfer,
        ItemTransfer $itemTransfer,
        ?ItemTransfer $itemInQuote = null
    ): PriceProductFilterTransfer {
        $priceProductFilterTransfer->fromArray($itemTransfer->toArray(), true);

        if ($itemInQuote !== null) {
            $priceProductFilterTransfer->setQuantity($itemTransfer->getQuantity() + $itemInQuote->getQuantity());
        }

        return $priceProductFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $cartChangeItemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function getItemInQuote(ItemTransfer $cartChangeItemTransfer, QuoteTransfer $quoteTransfer): ?ItemTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() === $cartChangeItemTransfer->getSku()) {
                return $itemTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessage(ItemTransfer $itemTransfer)
    {
        return (new MessageTransfer())
         ->setValue(static::CART_PRE_CHECK_PRICE_FAILED_TRANSLATION_KEY)
         ->setParameters(['%sku%' => $itemTransfer->getSku()]);
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
     * @return string
     */
    protected function getCurrencyCode(QuoteTransfer $quoteTransfer): string
    {
        if ($quoteTransfer->getCurrency() === null || $quoteTransfer->getCurrency()->getCode() === null) {
            return $this->currencyFacade->getCurrent()->getCode();
        }

        return $quoteTransfer->getCurrency()->getCode();
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
}
