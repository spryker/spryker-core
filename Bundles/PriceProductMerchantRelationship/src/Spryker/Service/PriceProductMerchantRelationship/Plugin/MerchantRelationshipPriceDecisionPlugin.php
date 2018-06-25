<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductMerchantRelationship\Plugin;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\Kernel\AbstractPlugin;
use Spryker\Service\PriceProduct\Dependency\Plugin\PriceProductDecisionPluginInterface;
use Spryker\Shared\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConstants;

class MerchantRelationshipPriceDecisionPlugin extends AbstractPlugin implements PriceProductDecisionPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function matchPriceByPriceProductCriteria(
        array $priceProductTransferCollection,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ?PriceProductTransfer {

        if (empty($priceProductTransferCollection)) {
            return null;
        }

        foreach ($priceProductTransferCollection as $priceProductTransfer) {
            $priceProductTransfer
                ->requirePriceDimension()
                ->requirePriceTypeName()
                ->requireMoneyValue();

            $priceProductTransfer->getMoneyValue()->requireCurrency();
            $priceProductTransfer->getMoneyValue()->getCurrency()->requireIdCurrency();

            if ($priceProductTransfer->getPriceDimension()->getIdMerchantRelationship()) {
                if ($priceProductTransfer->getMoneyValue()->getCurrency()->getIdCurrency() !== $priceProductCriteriaTransfer->getIdCurrency()) {
                    continue;
                }

                return $priceProductTransfer;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function matchPriceByPriceProductFilter(array $priceProductTransferCollection, PriceProductFilterTransfer $priceProductFilterTransfer): ?PriceProductTransfer
    {
        if (empty($priceProductTransferCollection)) {
            return null;
        }

        foreach ($priceProductTransferCollection as $priceProductTransfer) {
            $priceProductTransfer
                ->requirePriceDimension()
                ->requirePriceTypeName()
                ->requireMoneyValue();

            $priceProductTransfer->getMoneyValue()->requireCurrency();
            $priceProductTransfer->getMoneyValue()->getCurrency()->requireCode();

            if ($priceProductTransfer->getPriceDimension()->getType() === $this->getDimensionName()) {
                if ($priceProductTransfer->getMoneyValue()->getCurrency()->getCode() !== $priceProductFilterTransfer->getCurrencyIsoCode()) {
                    continue;
                }

                return $priceProductTransfer;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getDimensionName(): string
    {
        return PriceProductMerchantRelationshipConstants::PRICE_DIMENSION_MERCHANT_RELATIONSHIP;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteValid(?QuoteTransfer $quoteTransfer = null): bool
    {
        if (!$quoteTransfer) {
            return false;
        }

        if (!$quoteTransfer->getCustomer()) {
            return false;
        }

        if (!$quoteTransfer->getCustomer()->getCompanyUserTransfer()) {
            return false;
        }

        return true;
    }
}
