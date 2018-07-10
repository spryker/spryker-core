<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\VolumePriceProduct\Business\VolumePriceExtractor;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Client\VolumePriceProduct\Dependency\Service\VolumePriceProductToUtilEncodingServiceInterface;

class VolumePriceExtractor implements VolumePriceExtractorInterface
{
    /**
     * @see \Spryker\Shared\VolumePriceProduct\VolumePriceProductConfig::VOLUME_PRICE_TYPE
     */
    protected const VOLUME_PRICE_TYPE = 'volume_prices';

    protected const VOLUME_PRICE_QUANTITY = 'quantity';
    protected const VOLUME_PRICE_NET_PRICE = 'net_price';
    protected const VOLUME_PRICE_GROSS_PRICE = 'gross_price';

    /**
     * @var \Spryker\Zed\VolumePriceProduct\Dependency\Service\VolumePriceProductToUtilEncodingServiceInterface
     */
    protected $utilEncoding;

    /**
     * @param \Spryker\Client\VolumePriceProduct\Dependency\Service\VolumePriceProductToUtilEncodingServiceInterface $utilEncoding
     */
    public function __construct(VolumePriceProductToUtilEncodingServiceInterface $utilEncoding)
    {
        $this->utilEncoding = $utilEncoding;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractVolumePriceProducts(PriceProductTransfer $priceProductTransfer): array
    {
        if (!$priceProductTransfer->getMoneyValue()->getPriceData()) {
            return [];
        }

        $priceProductTransfers = [];
        $priceData = $this->utilEncoding->decodeJson($priceProductTransfer->getMoneyValue()->getPriceData(), true);

        if (!is_array($priceData) || !isset($priceData[static::VOLUME_PRICE_TYPE])) {
            return [];
        }

        foreach ($priceData[static::VOLUME_PRICE_TYPE] as $volumePrice) {
            $priceProductTransfers[] = $this->mapVolumePriceToPriceProductTransfer($priceProductTransfer, $volumePrice);
        }

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param array $volumePrice
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function mapVolumePriceToPriceProductTransfer(PriceProductTransfer $priceProductTransfer, array $volumePrice): PriceProductTransfer
    {
        $volumePriceTransfer = new PriceProductTransfer();
        $volumePriceTransfer->fromArray($priceProductTransfer->toArray(), true);
        $volumePriceTransfer->getMoneyValue()->setGrossAmount($volumePrice[static::VOLUME_PRICE_GROSS_PRICE])
            ->setNetAmount($volumePrice[static::VOLUME_PRICE_NET_PRICE])
            ->setPriceData(null);
        $volumePriceTransfer->setQuantityToApply($volumePrice[static::VOLUME_PRICE_QUANTITY]);

        return $volumePriceTransfer;
    }
}
