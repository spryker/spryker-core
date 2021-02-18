<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PriceProductOfferVolumesRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\RestProductOfferPricesAttributesTransfer;
use Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer;
use Spryker\Glue\PriceProductOfferVolumesRestApi\Dependency\Service\PriceProductOfferVolumesRestApiToUtilEncodingServiceInterface;

class RestProductOfferPricesAttributesMapper implements RestProductOfferPricesAttributesMapperInterface
{
    /**
     * @uses \Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig::VOLUME_PRICE_TYPE
     */
    protected const VOLUME_PRICE_KEY = 'volume_prices';

    /**
     * @uses \Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig::VOLUME_PRICE_QUANTITY
     */
    public const VOLUME_PRICE_QUANTITY_KEY = 'quantity';

    /**
     * @uses \Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig::VOLUME_PRICE_NET_PRICE
     */
    public const VOLUME_PRICE_NET_PRICE_KEY = 'net_price';

    /**
     * @uses \Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig::VOLUME_PRICE_GROSS_PRICE
     */
    public const VOLUME_PRICE_GROSS_PRICE_KEY = 'gross_price';

    /**
     * @var \Spryker\Glue\PriceProductOfferVolumesRestApi\Dependency\Service\PriceProductOfferVolumesRestApiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * ceProductOfferVolumeClient
     *
     * @param \Spryker\Glue\PriceProductOfferVolumesRestApi\Dependency\Service\PriceProductOfferVolumesRestApiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        PriceProductOfferVolumesRestApiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     * @param \Generated\Shared\Transfer\RestProductOfferPricesAttributesTransfer $restProductOfferPricesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductOfferPricesAttributesTransfer
     */
    public function mapCurrentProductPriceTransferToRestProductOfferPricesAttributesTransfer(
        CurrentProductPriceTransfer $currentProductPriceTransfer,
        RestProductOfferPricesAttributesTransfer $restProductOfferPricesAttributesTransfer
    ): RestProductOfferPricesAttributesTransfer {
        if (!$currentProductPriceTransfer->getPriceData()) {
            return $restProductOfferPricesAttributesTransfer;
        }

        foreach ($restProductOfferPricesAttributesTransfer->getPrices() as $restProductOfferPriceAttributesTransfer) {
            $restProductPriceVolumesAttributesTransfers = $this->getRestProductPriceVolumesAttributesTransfers(
                $currentProductPriceTransfer
            );

            $restProductOfferPriceAttributesTransfer->setVolumePrices($restProductPriceVolumesAttributesTransfers);
        }

        return $restProductOfferPricesAttributesTransfer;
    }

    /**
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer>
     *
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer[]
     */
    protected function getRestProductPriceVolumesAttributesTransfers(
        CurrentProductPriceTransfer $currentProductPriceTransfer
    ): ArrayObject {
        $restProductPriceVolumesAttributesTransfers = new ArrayObject();

        $volumePricesData = $this->getVolumePricesData($currentProductPriceTransfer);
        if ($volumePricesData === null) {
            return $restProductPriceVolumesAttributesTransfers;
        }

        return $this->mapVolumePricesDataToRestProductPriceVolumesAttributesTransfers(
            $volumePricesData,
            $restProductPriceVolumesAttributesTransfers
        );
    }

    /**
     * @phpstan-return array<int, array> $volumePriceData
     *
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     *
     * @return array|null
     */
    protected function getVolumePricesData(
        CurrentProductPriceTransfer $currentProductPriceTransfer
    ): ?array {
        $moneyValueTransfer = (new MoneyValueTransfer())
            ->setPriceData($currentProductPriceTransfer->getPriceDataOrFail());

        $priceData = $this->utilEncodingService->decodeJson($moneyValueTransfer->getPriceDataOrFail(), true);
        if (!array_key_exists(static::VOLUME_PRICE_KEY, $priceData)) {
            return null;
        }

        return $priceData[static::VOLUME_PRICE_KEY];
    }

    /**
     * @phpstan-param array<int, array> $volumePricesData
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer> $restProductPriceVolumesAttributesTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer>
     *
     * @param array $volumePricesData
     * @param \ArrayObject|\Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer[] $restProductPriceVolumesAttributesTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer[]
     */
    protected function mapVolumePricesDataToRestProductPriceVolumesAttributesTransfers(
        array $volumePricesData,
        ArrayObject $restProductPriceVolumesAttributesTransfers
    ): ArrayObject {
        foreach ($volumePricesData as $volumePriceData) {
            $restProductPriceVolumesAttributesTransfer = $this->mapVolumePriceDataToRestProductPriceVolumesAttributes(
                $volumePriceData,
                new RestProductPriceVolumesAttributesTransfer()
            );

            $restProductPriceVolumesAttributesTransfers->append($restProductPriceVolumesAttributesTransfer);
        }

        return $restProductPriceVolumesAttributesTransfers;
    }

    /**
     * @phpstan-param array<string, mixed> $volumePriceData
     *
     * @param array $volumePriceData
     * @param \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer $restProductPriceVolumesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer
     */
    protected function mapVolumePriceDataToRestProductPriceVolumesAttributes(
        array $volumePriceData,
        RestProductPriceVolumesAttributesTransfer $restProductPriceVolumesAttributesTransfer
    ): RestProductPriceVolumesAttributesTransfer {
        $restProductPriceVolumesAttributesTransfer
            ->setGrossAmount($volumePriceData[static::VOLUME_PRICE_GROSS_PRICE_KEY])
            ->setNetAmount($volumePriceData[static::VOLUME_PRICE_NET_PRICE_KEY])
            ->setQuantity($volumePriceData[static::VOLUME_PRICE_QUANTITY_KEY]);

        return $restProductPriceVolumesAttributesTransfer;
    }
}
