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
use Generated\Shared\Transfer\RestProductPriceAttributesTransfer;
use Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer;
use Spryker\Glue\PriceProductVolumesRestApi\Dependency\Client\PriceProductVolumesRestApiToPriceProductVolumeClientInterface;

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
     * @param \Spryker\Glue\PriceProductVolumesRestApi\Dependency\Client\PriceProductVolumesRestApiToPriceProductVolumeClientInterface $priceProductVolumeClient
     */
    public function __construct(
        PriceProductVolumesRestApiToPriceProductVolumeClientInterface $priceProductVolumeClient
    ) {
        $this->priceProductVolumeClient = $priceProductVolumeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     * @param \Generated\Shared\Transfer\RestProductPriceAttributesTransfer $restProductPriceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductPriceAttributesTransfer
     */
    public function mapPriceProductVolumeDataToRestProductPricesAttributes(
        CurrentProductPriceTransfer $currentProductPriceTransfer,
        RestProductPriceAttributesTransfer $restProductPriceAttributesTransfer
    ): RestProductPriceAttributesTransfer {
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
                $priceProductTransfer,
                new RestProductPriceVolumesAttributesTransfer()
            );
        }

        return $restProductPriceVolumesAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer $restProductPriceVolumesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer
     */
    protected function mapMoneyValueTransferToRestProductPriceVolumesAttributesTransfer(
        PriceProductTransfer $priceProductTransfer,
        RestProductPriceVolumesAttributesTransfer $restProductPriceVolumesAttributesTransfer
    ): RestProductPriceVolumesAttributesTransfer {
        $restProductPriceVolumesAttributesTransfer->fromArray($priceProductTransfer->getMoneyValueOrFail()->toArray(), true);
        $restProductPriceVolumesAttributesTransfer->setQuantity($priceProductTransfer->getVolumeQuantity());

        return $restProductPriceVolumesAttributesTransfer;
    }
}
