<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\RestCurrencyTransfer;
use Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer;

class ProductConfigurationInstancePriceMapper implements ProductConfigurationInstancePriceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer[]|\ArrayObject $restProductConfigurationPriceAttributesTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer[]|\ArrayObject $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]|\ArrayObject
     */
    public function mapRestProductConfigurationPriceAttributesTransfersToPriceProductTransfers(
        ArrayObject $restProductConfigurationPriceAttributesTransfers,
        ArrayObject $priceProductTransfers
    ): ArrayObject {
        if ($restProductConfigurationPriceAttributesTransfers->count() === 0) {
            return $priceProductTransfers;
        }

        foreach ($restProductConfigurationPriceAttributesTransfers as $restProductConfigurationPriceAttributesTransfer) {
            $priceProductTransfer = $this->mapRestProductConfigurationPriceAttributesTransferToPriceProductTransfer(
                $restProductConfigurationPriceAttributesTransfer,
                new PriceProductTransfer()
            );

            $priceProductTransfers->append($priceProductTransfer);
        }

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[]|\ArrayObject $priceProductTransfers
     * @param \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer[]|\ArrayObject $restProductConfigurationPriceAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer[]|\ArrayObject
     */
    public function mapPriceProductTransfersToRestProductConfigurationPriceAttributesTransfers(
        ArrayObject $priceProductTransfers,
        ArrayObject $restProductConfigurationPriceAttributesTransfers
    ): ArrayObject {
        if ($priceProductTransfers->count() === 0) {
            return $restProductConfigurationPriceAttributesTransfers;
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $restProductConfigurationPriceAttributesTransfer = $this->mapPriceProductTransferToRestProductConfigurationPriceAttributesTransfer(
                $priceProductTransfer,
                new RestProductConfigurationPriceAttributesTransfer()
            );

            $restProductConfigurationPriceAttributesTransfers->append($restProductConfigurationPriceAttributesTransfer);
        }

        return $restProductConfigurationPriceAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer $restProductConfigurationPriceAttributesTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function mapRestProductConfigurationPriceAttributesTransferToPriceProductTransfer(
        RestProductConfigurationPriceAttributesTransfer $restProductConfigurationPriceAttributesTransfer,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        $currencyTransfer = (new CurrencyTransfer())
            ->fromArray($restProductConfigurationPriceAttributesTransfer->getCurrencyOrFail()->toArray(), true);
        $moneyValueTransfer = (new MoneyValueTransfer())
            ->fromArray($restProductConfigurationPriceAttributesTransfer->toArray(), true)
            ->setCurrency($currencyTransfer);

        return $priceProductTransfer
            ->fromArray($restProductConfigurationPriceAttributesTransfer->toArray(), true)
            ->setMoneyValue($moneyValueTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer $restProductConfigurationPriceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer
     */
    protected function mapPriceProductTransferToRestProductConfigurationPriceAttributesTransfer(
        PriceProductTransfer $priceProductTransfer,
        RestProductConfigurationPriceAttributesTransfer $restProductConfigurationPriceAttributesTransfer
    ): RestProductConfigurationPriceAttributesTransfer {
        $restCurrencyTransfer = (new RestCurrencyTransfer())
            ->fromArray($priceProductTransfer->getMoneyValueOrFail()->getCurrencyOrFail()->toArray(), true);

        return $restProductConfigurationPriceAttributesTransfer
            ->fromArray($priceProductTransfer->toArray(), true)
            ->setNetAmount($priceProductTransfer->getMoneyValueOrFail()->getNetAmount())
            ->setGrossAmount($priceProductTransfer->getMoneyValueOrFail()->getGrossAmount())
            ->setCurrency($restCurrencyTransfer);
    }
}
