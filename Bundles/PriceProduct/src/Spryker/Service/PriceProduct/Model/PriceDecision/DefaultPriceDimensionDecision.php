<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\Model\PriceDecision;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer;

class DefaultPriceDimensionDecision implements DefaultPriceDecisionInterface
{
    protected const GROSS_PRICE = 'GROSS_PRICE';
    protected const NET_PRICE = 'NET_PRICE';

    /**
     * @param \Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer[] $priceProductStoreEntityTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    public function matchValue(
        array $priceProductStoreEntityTransferCollection,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ?MoneyValueTransfer {
        foreach ($priceProductStoreEntityTransferCollection as $priceProductStoreEntityTransfer) {
            if (!$this->isDefaultPriceSet($priceProductStoreEntityTransfer->virtualProperties())) {
                continue;
            }

            return $this->getMoneyValueTransfer($priceProductCriteriaTransfer, $priceProductStoreEntityTransfer);
        }

        return null;
    }

    /**
     * @param array $virtualProperties
     *
     * @return bool
     */
    protected function isDefaultPriceSet(array $virtualProperties): bool
    {
        return !empty($virtualProperties[PriceProductDimensionTransfer::ID_PRICE_PRODUCT_DEFAULT]);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     * @param \Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer $priceProductStoreEntityTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function getMoneyValueTransfer(
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer,
        SpyPriceProductStoreEntityTransfer $priceProductStoreEntityTransfer
    ): MoneyValueTransfer {
        $moneyValueTransfer = (new MoneyValueTransfer())
            ->fromArray($priceProductStoreEntityTransfer->toArray(), true);

        return $moneyValueTransfer
            ->setNetAmount($priceProductStoreEntityTransfer->getNetPrice())
            ->setGrossAmount($priceProductStoreEntityTransfer->getGrossPrice());
    }
}
