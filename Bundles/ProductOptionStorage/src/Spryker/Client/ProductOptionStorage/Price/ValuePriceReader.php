<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOptionStorage\Price;

use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;
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
    public function __construct(
        ProductOptionStorageToCurrencyClientInterface $currencyClient,
        ProductOptionStorageToPriceClientInterface $priceClient
    ) {
        $this->currencyClient = $currencyClient;
        $this->priceClient = $priceClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer
     */
    public function resolveProductAbstractOptionStorageTransferProductOptionValuePrices(
        ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer
    ): ProductAbstractOptionStorageTransfer {
        $currentCurrencyCode = $this->getCurrentCurrencyCode();
        $currentPriceMode = $this->getCurrentPriceMode();
        $productOptionValueStorageTransfers = [];
        foreach ($productAbstractOptionStorageTransfer->getProductOptionGroups() as $productOptionGroupStorageTransfer) {
            $productOptionValueStorageTransfers[] = $productOptionGroupStorageTransfer->getProductOptionValues()
                ->getArrayCopy();
        }
        $productOptionValueStorageTransfers = array_merge(...$productOptionValueStorageTransfers);

        foreach ($productOptionValueStorageTransfers as $productOptionValueStorageTransfer) {
            $this->resolveProductOptionValuePrice(
                $productOptionValueStorageTransfer,
                $currentCurrencyCode,
                $currentPriceMode
            );
        }

        return $productAbstractOptionStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer[] $productAbstractOptionStorageTransfers
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer[]
     */
    public function resolveProductAbstractOptionStorageTransfersProductOptionValuePrices(
        array $productAbstractOptionStorageTransfers
    ): array {
        foreach ($productAbstractOptionStorageTransfers as $productAbstractOptionStorageTransfer) {
            $this->resolveProductAbstractOptionStorageTransferProductOptionValuePrices(
                $productAbstractOptionStorageTransfer
            );
        }

        return $productAbstractOptionStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueStorageTransfer $productOptionValueStorageTransfer
     * @param string $currencyCode
     * @param string $priceMode
     *
     * @return void
     */
    protected function resolveProductOptionValuePrice(
        ProductOptionValueStorageTransfer $productOptionValueStorageTransfer,
        string $currencyCode,
        string $priceMode
    ): void {
        $prices = $productOptionValueStorageTransfer->getPrices();
        $price = $prices[$currencyCode][$priceMode][ProductOptionConstants::AMOUNT] ?? null;
        $productOptionValueStorageTransfer->setPrice($price);
    }

    /**
     * @return string
     */
    protected function getCurrentCurrencyCode(): string
    {
        if (!isset(static::$currentCurrencyCodeBuffer)) {
            static::$currentCurrencyCodeBuffer = $this->currencyClient->getCurrent()->getCode();
        }

        return static::$currentCurrencyCodeBuffer;
    }

    /**
     * @return string
     */
    protected function getCurrentPriceMode(): string
    {
        if (!isset(static::$currentPriceModeBuffer)) {
            static::$currentPriceModeBuffer = $this->priceClient->getCurrentPriceMode();
        }

        return static::$currentPriceModeBuffer;
    }
}
