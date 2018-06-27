<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductMerchantRelationship\Plugin\PriceProductExtension;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\Kernel\AbstractPlugin;
use Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductDecisionPluginInterface;

/**
 * @method \Spryker\Service\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig getConfig()
 */
class MerchantRelationshipPriceProductDecisionPlugin extends AbstractPlugin implements PriceProductDecisionPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function matchPriceByPriceProductCriteria(
        array $priceProductTransfers,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ?PriceProductTransfer {
        $matchedPriceProductTransfer = null;

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceProductTransfer
                ->requirePriceDimension()
                ->requirePriceTypeName()
                ->requireMoneyValue();

            if (!$priceProductTransfer->getPriceDimension()->getIdMerchantRelationship()) {
                continue;
            }

            $priceProductTransfer->getMoneyValue()->requireCurrency();

            if ($priceProductTransfer->getMoneyValue()->getCurrency()->getIdCurrency() !== $priceProductCriteriaTransfer->getIdCurrency()) {
                continue;
            }

            if ($matchedPriceProductTransfer === null ||
                $matchedPriceProductTransfer->getMoneyValue()->getGrossAmount() > $priceProductTransfer->getMoneyValue()->getGrossAmount()
            ) {
                $matchedPriceProductTransfer = $priceProductTransfer;
            }
        }

        return $matchedPriceProductTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function matchPriceByPriceProductFilter(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): ?PriceProductTransfer
    {
        if (empty($priceProductTransfers)) {
            return null;
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
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
        return $this->getConfig()->getPriceDimensionMerchantRelationship();
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
