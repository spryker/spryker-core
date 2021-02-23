<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer;
use Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer;
use Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer;

class ProductConfigurationPriceProductVolumeMapper implements ProductConfigurationPriceProductVolumeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     * @param \Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer $restCartItemProductConfigurationInstanceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer
     */
    public function mapProductConfigurationInstanceToRestCartItemProductConfigurationInstanceAttributes(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer,
        RestCartItemProductConfigurationInstanceAttributesTransfer $restCartItemProductConfigurationInstanceAttributesTransfer
    ): RestCartItemProductConfigurationInstanceAttributesTransfer {
        if ($productConfigurationInstanceTransfer->getPrices()->count() === 0) {
            return $restCartItemProductConfigurationInstanceAttributesTransfer;
        }

        $volumePriceProductTransfers = $this->extractVolumePriceProductTransfers($productConfigurationInstanceTransfer->getPrices());

        $restProductConfigurationPriceAttributesTransfers = $this->getRestProductConfigurationPriceAttributesTransfers(
            $productConfigurationInstanceTransfer,
            $restCartItemProductConfigurationInstanceAttributesTransfer,
            $volumePriceProductTransfers
        );

        return $restCartItemProductConfigurationInstanceAttributesTransfer->setPrices(new ArrayObject($restProductConfigurationPriceAttributesTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $volumePriceProductTransfers
     * @param \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer $restProductConfigurationPriceAttributesTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer
     */
    protected function mapVolumePriceProductTransfersToRestCartItemProductConfigurationInstanceAttributesTransfer(
        array $volumePriceProductTransfers,
        RestProductConfigurationPriceAttributesTransfer $restProductConfigurationPriceAttributesTransfer,
        PriceProductTransfer $priceProductTransfer
    ): RestProductConfigurationPriceAttributesTransfer {
        foreach ($volumePriceProductTransfers as $volumePriceProductTransfer) {
            if ($volumePriceProductTransfer->getPriceTypeName() !== $priceProductTransfer->getPriceTypeName()) {
                continue;
            }

            $restProductPriceVolumesAttributesTransfer = $this->mapPriceProductTransferToRestProductPriceVolumesAttributesTransfer(
                $priceProductTransfer,
                new RestProductPriceVolumesAttributesTransfer()
            );
            $restProductPriceVolumesAttributesTransfer->setQuantity($volumePriceProductTransfer->getVolumeQuantity());
            $restProductConfigurationPriceAttributesTransfer->addVolumePrice($restProductPriceVolumesAttributesTransfer);
        }

        return $restProductConfigurationPriceAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer $restProductPriceVolumesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer
     */
    protected function mapPriceProductTransferToRestProductPriceVolumesAttributesTransfer(
        PriceProductTransfer $priceProductTransfer,
        RestProductPriceVolumesAttributesTransfer $restProductPriceVolumesAttributesTransfer
    ): RestProductPriceVolumesAttributesTransfer {
        $restProductPriceVolumesAttributesTransfer->fromArray($priceProductTransfer->getMoneyValueOrFail()->toArray(), true);
        $restProductPriceVolumesAttributesTransfer->setQuantity($priceProductTransfer->getVolumeQuantity());

        return $restProductPriceVolumesAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[]|\ArrayObject $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function extractVolumePriceProductTransfers(ArrayObject $priceProductTransfers): array
    {
        return array_filter($priceProductTransfers->getArrayCopy(), function (PriceProductTransfer $priceProductTransfer) {
            return $priceProductTransfer->getMoneyValueOrFail()->getPriceData() !== null;
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     * @param \Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer $restCartItemProductConfigurationInstanceAttributesTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $volumePriceProductTransfers
     *
     * @return \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer[]
     */
    protected function getRestProductConfigurationPriceAttributesTransfers(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer,
        RestCartItemProductConfigurationInstanceAttributesTransfer $restCartItemProductConfigurationInstanceAttributesTransfer,
        array $volumePriceProductTransfers
    ): array {
        $restProductConfigurationPriceAttributesTransfers = [];

        foreach ($productConfigurationInstanceTransfer->getPrices() as $priceProductTransfer) {
            if ($priceProductTransfer->getVolumeQuantity() !== null) {
                continue;
            }

            $restProductConfigurationPriceAttributesTransferToMap = $this->extractRestProductConfigurationPriceAttributesTransfer(
                $priceProductTransfer,
                $restCartItemProductConfigurationInstanceAttributesTransfer->getPrices()
            );

            if (!$restProductConfigurationPriceAttributesTransferToMap) {
                continue;
            }

            $restProductConfigurationPriceAttributesTransfers[] = $this->mapVolumePriceProductTransfersToRestCartItemProductConfigurationInstanceAttributesTransfer(
                $volumePriceProductTransfers,
                $restProductConfigurationPriceAttributesTransferToMap,
                $priceProductTransfer
            );
        }

        return $restProductConfigurationPriceAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer[]|\ArrayObject $restProductConfigurationPriceAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer|null
     */
    protected function extractRestProductConfigurationPriceAttributesTransfer(
        PriceProductTransfer $priceProductTransfer,
        ArrayObject $restProductConfigurationPriceAttributesTransfers
    ): ?RestProductConfigurationPriceAttributesTransfer {
        foreach ($restProductConfigurationPriceAttributesTransfers as $restProductConfigurationPriceAttributesTransfer) {
            if (
                $this->isSamePriceProduct(
                    $priceProductTransfer,
                    $restProductConfigurationPriceAttributesTransfer
                )
            ) {
                return $restProductConfigurationPriceAttributesTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer $restProductConfigurationPriceAttributesTransfer
     *
     * @return bool
     */
    protected function isSamePriceProduct(
        PriceProductTransfer $priceProductTransfer,
        RestProductConfigurationPriceAttributesTransfer $restProductConfigurationPriceAttributesTransfer
    ): bool {
        return $priceProductTransfer->getPriceTypeName() === $restProductConfigurationPriceAttributesTransfer->getPriceTypeName()
            && $priceProductTransfer->getMoneyValueOrFail()->getGrossAmount() === $restProductConfigurationPriceAttributesTransfer->getGrossAmount()
            && $priceProductTransfer->getMoneyValueOrFail()->getNetAmount() === $restProductConfigurationPriceAttributesTransfer->getNetAmount()
            && $priceProductTransfer->getMoneyValueOrFail()->getCurrencyOrFail()->getName() === $restProductConfigurationPriceAttributesTransfer->getCurrencyOrFail()->getName();
    }
}
