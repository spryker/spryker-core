<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductMerchantRelationship\Plugin\PriceProductExtension;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Service\Kernel\AbstractPlugin;
use Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductFilterPluginInterface;
use Spryker\Shared\PriceProduct\PriceProductConfig;
use Spryker\Shared\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig;

/**
 * @method \Spryker\Service\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig getConfig()
 */
class MerchantRelationshipPriceProductFilterPlugin extends AbstractPlugin implements PriceProductFilterPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function filter(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): array
    {
        $priceProductFilterTransfer->requirePriceMode();

        $resultPriceProductTransfers = [];
        $minPriceProductTransfer = null;

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceProductTransfer->requirePriceDimension();

            if (!$priceProductTransfer->getPriceDimension()->getIdMerchantRelationship()) {
                $resultPriceProductTransfers[] = $priceProductTransfer;
            }

            if ($minPriceProductTransfer === null || !$this->hasPriceByPriceMode($minPriceProductTransfer, $priceProductFilterTransfer->getPriceMode())) {
                $minPriceProductTransfer = $priceProductTransfer;
                continue;
            }

            if (!$this->hasPriceByPriceMode($priceProductTransfer, $priceProductFilterTransfer->getPriceMode())) {
                continue;
            }

            if ($priceProductFilterTransfer->getPriceMode() === PriceProductConfig::PRICE_GROSS_MODE) {
                if ($minPriceProductTransfer->getMoneyValue()->getGrossAmount() > $priceProductTransfer->getMoneyValue()->getGrossAmount()) {
                    $minPriceProductTransfer = $priceProductTransfer;
                }
                continue;
            }

            if ($minPriceProductTransfer->getMoneyValue()->getNetAmount() > $priceProductTransfer->getMoneyValue()->getNetAmount()) {
                $minPriceProductTransfer = $priceProductTransfer;
            }
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
            if ($minPriceProductTransfer->getPriceDimension()->getIdMerchantRelationship() === $priceProductTransfer->getPriceDimension()->getIdMerchantRelationship()) {
                $resultPriceProductTransfers[] = $priceProductTransfer;
            }
        }

        return $resultPriceProductTransfers;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getDimensionName(): string
    {
        return PriceProductMerchantRelationshipConfig::PRICE_DIMENSION_MERCHANT_RELATIONSHIP;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param string $priceMode
     *
     * @return bool
     */
    protected function hasPriceByPriceMode(PriceProductTransfer $priceProductTransfer, string $priceMode): bool
    {
        return ($priceMode === PriceProductConfig::PRICE_GROSS_MODE && $priceProductTransfer->getMoneyValue()->getGrossAmount() !== null) ||
            ($priceMode !== PriceProductConfig::PRICE_GROSS_MODE && $priceProductTransfer->getMoneyValue()->getNetAmount() !== null);
    }
}
