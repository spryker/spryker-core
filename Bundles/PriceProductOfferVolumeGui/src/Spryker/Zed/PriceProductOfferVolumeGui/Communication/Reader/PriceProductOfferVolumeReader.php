<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolumeGui\Communication\Reader;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\PriceProductOfferVolumeGui\Dependency\Service\PriceProductOfferVolumeGuiToUtilEncodingServiceInterface;

class PriceProductOfferVolumeReader implements PriceProductOfferVolumeReaderInterface
{
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
     * @phpstan-return array<string, mixed>
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param string $storeName
     * @param string $currencyCode
     *
     * @return array
     */
    public function getVolumePricesData(
        ProductOfferTransfer $productOfferTransfer,
        string $storeName,
        string $currencyCode
    ): array {
        $data = [];

        foreach ($productOfferTransfer->getPrices() as $priceProductTransfer) {
            if (!$priceProductTransfer->getMoneyValue()) {
                continue;
            }

            $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
            $priceData = $this->utilEncodingService
                ->decodeJson($priceProductTransfer->getMoneyValue()->getPriceData(), true);

            if (
                $moneyValueTransfer->getCurrency()->getCode() === $currencyCode
                && $moneyValueTransfer->getStore()->getName() === $storeName
                && isset($priceData['volume_prices'])
            ) {
                $data['priceProduct'] = $priceProductTransfer;
                $data['volumePrices'] = $priceData['volume_prices'];
            }
        }

        return $data;
    }
}
