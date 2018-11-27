<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOptionStorage\Price;

use Generated\Shared\Transfer\ProductOptionGroupStorageTransfer;
use Generated\Shared\Transfer\ProductOptionValueStorageTransfer;
use Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToCurrencyClientInterface;
use Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToPriceClientInterface;
use Spryker\Shared\ProductOption\ProductOptionConstants;

class ValuePriceReader implements ValuePriceReaderInterface
{
    /**
     * @var \Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @var \Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToPriceClientInterface
     */
    protected $priceClient;

    /**
     * @var string|null
     */
    protected static $currentCurrencyCodeBuffer;

    /**
     * @var string|null
     */
    protected static $currentPriceModeBuffer;

    /**
     * @param \Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToCurrencyClientInterface $currencyClient
     * @param \Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToPriceClientInterface $priceClient
     */
    public function __construct(ProductOptionStorageToCurrencyClientInterface $currencyClient, ProductOptionStorageToPriceClientInterface $priceClient)
    {
        $this->currencyClient = $currencyClient;
        $this->priceClient = $priceClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupStorageTransfer $productOptionGroupStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupStorageTransfer
     */
    public function resolvePrices(ProductOptionGroupStorageTransfer $productOptionGroupStorageTransfer)
    {
        $currentCurrencyCode = $this->getCurrentCurrencyCode();
        $currentPriceMode = $this->getCurrentPriceMode();

        foreach ($productOptionGroupStorageTransfer->getProductOptionValues() as $productOptionValue) {
            $this->resolveValuePrice($productOptionValue, $currentCurrencyCode, $currentPriceMode);
        }

        return $productOptionGroupStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueStorageTransfer $productOptionValueStorageTransfer
     * @param string $currencyCode
     * @param string $priceMode
     *
     * @return void
     */
    protected function resolveValuePrice(ProductOptionValueStorageTransfer $productOptionValueStorageTransfer, $currencyCode, $priceMode)
    {
        $prices = $productOptionValueStorageTransfer->getPrices();

        $productOptionValueStorageTransfer->setPrice(
            isset($prices[$currencyCode]) ?
                $prices[$currencyCode][$priceMode][ProductOptionConstants::AMOUNT] :
                null
        );
    }

    /**
     * @return string
     */
    protected function getCurrentCurrencyCode()
    {
        if (!isset(static::$currentCurrencyCodeBuffer)) {
            static::$currentCurrencyCodeBuffer = $this->currencyClient->getCurrent()->getCode();
        }

        return static::$currentCurrencyCodeBuffer;
    }

    /**
     * @return string
     */
    protected function getCurrentPriceMode()
    {
        if (!isset(static::$currentPriceModeBuffer)) {
            static::$currentPriceModeBuffer = $this->priceClient->getCurrentPriceMode();
        }

        return static::$currentPriceModeBuffer;
    }
}
