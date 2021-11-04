<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOption\OptionGroup;

use ArrayObject;
use Generated\Shared\Transfer\StorageProductOptionGroupTransfer;
use Generated\Shared\Transfer\StorageProductOptionValueTransfer;
use Spryker\Client\ProductOption\Dependency\Client\ProductOptionToCurrencyClientInterface;
use Spryker\Client\ProductOption\Dependency\Client\ProductOptionToPriceClientInterface;
use Spryker\Shared\ProductOption\ProductOptionConstants;

class ProductOptionValuePriceReader implements ProductOptionValuePriceReaderInterface
{
    /**
     * @var \Spryker\Client\ProductOption\Dependency\Client\ProductOptionToPriceClientInterface
     */
    protected $priceClient;

    /**
     * @var \Spryker\Client\ProductOption\Dependency\Client\ProductOptionToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @var string
     */
    protected static $currentCurrencyCodeBuffer;

    /**
     * @var string
     */
    protected static $currentPriceModeBuffer;

    /**
     * @param \Spryker\Client\ProductOption\Dependency\Client\ProductOptionToPriceClientInterface $priceClient
     * @param \Spryker\Client\ProductOption\Dependency\Client\ProductOptionToCurrencyClientInterface $currencyClient
     */
    public function __construct(ProductOptionToPriceClientInterface $priceClient, ProductOptionToCurrencyClientInterface $currencyClient)
    {
        $this->priceClient = $priceClient;
        $this->currencyClient = $currencyClient;
    }

    /**
     * @param \Generated\Shared\Transfer\StorageProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    public function localizeGroupPrices(StorageProductOptionGroupTransfer $productOptionGroupTransfer)
    {
        $currentCurrencyCode = $this->getCurrentCurrencyCode();
        $currentPriceMode = $this->getCurrentPriceMode();

        foreach ($productOptionGroupTransfer->getValues() as $productOptionValueTransfer) {
            $this->localizeOptionValuePrice($productOptionValueTransfer, $currentCurrencyCode, $currentPriceMode);
        }

        $productOptionGroupTransfer->setValues(
            $this->filterOptionValuesWithEmptyPrice($productOptionGroupTransfer->getValues()),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StorageProductOptionValueTransfer $productOptionValueTransfer
     * @param string $currencyCode
     * @param string $priceMode
     *
     * @return void
     */
    protected function localizeOptionValuePrice(StorageProductOptionValueTransfer $productOptionValueTransfer, $currencyCode, $priceMode)
    {
        $prices = $productOptionValueTransfer->getPrices();

        $productOptionValueTransfer->setPrice(
            isset($prices[$currencyCode]) ?
                $prices[$currencyCode][$priceMode][ProductOptionConstants::AMOUNT] :
                null,
        );
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\StorageProductOptionValueTransfer> $productOptionValueCollection
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\StorageProductOptionValueTransfer>
     */
    protected function filterOptionValuesWithEmptyPrice(ArrayObject $productOptionValueCollection)
    {
        return new ArrayObject(
            array_filter((array)$productOptionValueCollection, function (StorageProductOptionValueTransfer $productOptionValueTransfer) {
                return $productOptionValueTransfer->getPrice() === null ? false : true;
            }),
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
