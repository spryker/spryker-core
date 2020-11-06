<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PriceProductVolumesRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\RestProductPricesAttributesTransfer;
use Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer;
use Spryker\Glue\PriceProductVolumesRestApi\Dependency\Client\PriceProductVolumesRestApiToPriceProductVolumeClientInterface;
use Spryker\Glue\PriceProductVolumesRestApi\Dependency\Service\PriceProductVolumesRestApiToUtilEncodingServiceInterface;

class PriceProductVolumeMapper implements PriceProductVolumeMapperInterface
{
    /**
     * @uses \Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig::VOLUME_PRICE_QUANTITY
     */
    protected const VOLUME_PRICE_QUANTITY = 'quantity';

    /**
     * @var \Spryker\Glue\PriceProductVolumesRestApi\Dependency\Client\PriceProductVolumesRestApiToPriceProductVolumeClientInterface
     */
    protected $priceProductVolumeClient;

    /**
     * @var \Spryker\Glue\PriceProductVolumesRestApi\Dependency\Service\PriceProductVolumesRestApiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Glue\PriceProductVolumesRestApi\Dependency\Client\PriceProductVolumesRestApiToPriceProductVolumeClientInterface $priceProductVolumeClient
     * @param \Spryker\Glue\PriceProductVolumesRestApi\Dependency\Service\PriceProductVolumesRestApiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        PriceProductVolumesRestApiToPriceProductVolumeClientInterface $priceProductVolumeClient,
        PriceProductVolumesRestApiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->priceProductVolumeClient = $priceProductVolumeClient;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     * @param \Generated\Shared\Transfer\RestProductPricesAttributesTransfer $restProductPriceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductPricesAttributesTransfer
     */
    public function mapPriceProductVolumeDataToRestProductPricesAttributes(
        CurrentProductPriceTransfer $currentProductPriceTransfer,
        RestProductPricesAttributesTransfer $restProductPriceAttributesTransfer
    ): RestProductPricesAttributesTransfer {
        if (!$currentProductPriceTransfer->getPriceData()) {
            return $restProductPriceAttributesTransfer;
        }

        $priceProductTransfer = $this->createPriceProductTransfer($currentProductPriceTransfer);

        $productPriceTransfers = $this->priceProductVolumeClient->extractProductPricesForProductAbstract([$priceProductTransfer]);
        $restProductPriceVolumesAttributesTransfers = $this->mapProductPriceTransfersToRestProductPriceVolumesAttributesTransfers(
            $productPriceTransfers,
            []
        );

        return $restProductPriceAttributesTransfer->setVolumePrices(new ArrayObject($restProductPriceVolumesAttributesTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductTransfer(CurrentProductPriceTransfer $currentProductPriceTransfer): PriceProductTransfer
    {
        $moneyValueTransfer = (new MoneyValueTransfer())->setPriceData($currentProductPriceTransfer->getPriceDataOrFail());

        return (new PriceProductTransfer())->setMoneyValue($moneyValueTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer[] $restProductPriceVolumesAttributesTransfers
     *
     * @return array
     */
    protected function mapProductPriceTransfersToRestProductPriceVolumesAttributesTransfers(
        array $priceProductTransfers,
        array $restProductPriceVolumesAttributesTransfers
    ): array {
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $restProductPriceVolumesAttributesTransfers[] = $this->mapMoneyValueTransferToRestProductPriceVolumesAttributesTransfer(
                $priceProductTransfer->getMoneyValueOrFail(),
                new RestProductPriceVolumesAttributesTransfer()
            );
        }

        return $restProductPriceVolumesAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer $restProductPriceVolumesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer
     */
    protected function mapMoneyValueTransferToRestProductPriceVolumesAttributesTransfer(
        MoneyValueTransfer $moneyValueTransfer,
        RestProductPriceVolumesAttributesTransfer $restProductPriceVolumesAttributesTransfer
    ): RestProductPriceVolumesAttributesTransfer {
        $restProductPriceVolumesAttributesTransfer->fromArray($moneyValueTransfer->toArray(), true);
        $restProductPriceVolumesAttributesTransfer->setQuantity($this->getVolumePriceQuantity($moneyValueTransfer));

        return $restProductPriceVolumesAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return int
     */
    protected function getVolumePriceQuantity(MoneyValueTransfer $moneyValueTransfer): int
    {
        $priceData = $moneyValueTransfer->getPriceData();
        $priceDataDecoded = $this->utilEncodingService->decodeJson($priceData, true);

        return $priceDataDecoded[static::VOLUME_PRICE_QUANTITY];
    }
}
