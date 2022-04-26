<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolumeGui\Communication\Reader;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\PriceProductOfferVolumeGui\Dependency\Service\PriceProductOfferVolumeGuiToUtilEncodingServiceInterface;

class PriceProductOfferVolumeReader implements PriceProductOfferVolumeReaderInterface
{
    /**
     * @var string
     */
    protected const KEY_PRICE_DATA_VOLUME_PRICES = 'volume_prices';

    /**
     * @var string
     */
    protected const KEY_VOLUME_PRICES = 'volumePrices';

    /**
     * @var string
     */
    protected const KEY_PRICE_PRODUCT = 'priceProduct';

    /**
     * @var \Spryker\Zed\PriceProductOfferVolumeGui\Dependency\Service\PriceProductOfferVolumeGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\PriceProductOfferVolumeGui\Dependency\Service\PriceProductOfferVolumeGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(PriceProductOfferVolumeGuiToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param string $storeName
     * @param string $currencyCode
     * @param string|null $priceType
     *
     * @return array<string, mixed>
     */
    public function getVolumePricesData(
        ProductOfferTransfer $productOfferTransfer,
        string $storeName,
        string $currencyCode,
        ?string $priceType = null
    ): array {
        $data = [];

        foreach ($productOfferTransfer->getPrices() as $priceProductTransfer) {
            $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
            if ($moneyValueTransfer === null) {
                continue;
            }

            $priceData = $this->utilEncodingService
                ->decodeJson($moneyValueTransfer->getPriceData(), true);

            if (!isset($priceData[static::KEY_PRICE_DATA_VOLUME_PRICES])) {
                continue;
            }

            if (!$this->isMoneyValueTransferSatisfiedByParameters($moneyValueTransfer, $storeName, $currencyCode)) {
                continue;
            }

            if ($priceType !== null && $priceProductTransfer->getPriceTypeOrFail()->getName() !== $priceType) {
                continue;
            }

            $data[static::KEY_PRICE_PRODUCT] = $priceProductTransfer;
            $data[static::KEY_VOLUME_PRICES] = $priceData[static::KEY_PRICE_DATA_VOLUME_PRICES];
        }

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param string $storeName
     * @param string $currencyCode
     *
     * @return bool
     */
    protected function isMoneyValueTransferSatisfiedByParameters(
        MoneyValueTransfer $moneyValueTransfer,
        string $storeName,
        string $currencyCode
    ): bool {
        if ($moneyValueTransfer->getStoreOrFail()->getName() !== $storeName) {
            return false;
        }

        if ($moneyValueTransfer->getCurrencyOrFail()->getCode() !== $currencyCode) {
            return false;
        }

        return true;
    }
}
