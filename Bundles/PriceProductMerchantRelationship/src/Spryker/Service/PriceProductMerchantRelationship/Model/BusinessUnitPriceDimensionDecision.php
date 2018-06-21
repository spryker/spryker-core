<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Business\Model;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer;
use Spryker\Zed\PriceProductMerchantRelationship\Dependency\Facade\PriceProductMerchantRelationshipToPriceProductFacadeInterface;

class BusinessUnitPriceDimensionDecision implements BusinessUnitPriceDecisionInterface
{
    protected const GROSS_PRICE = 'GROSS_PRICE';
    protected const NET_PRICE = 'NET_PRICE';

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationship\Dependency\Facade\PriceProductMerchantRelationshipToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationship\Dependency\Facade\PriceProductMerchantRelationshipToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(PriceProductMerchantRelationshipToPriceProductFacadeInterface $priceProductFacade)
    {
        $this->priceProductFacade = $priceProductFacade;
    }

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

        if (!$this->isQuoteValid($priceProductCriteriaTransfer->getQuote())) {
            return null;
        }

        foreach ($priceProductStoreEntityTransferCollection as $priceProductStoreEntityTransfer) {
            if (!$this->isBusinessUnitPriceSet($priceProductStoreEntityTransfer->virtualProperties())) {
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
    protected function isBusinessUnitPriceSet(array $virtualProperties): bool
    {
        return isset($virtualProperties[PriceProductDimensionTransfer::ID_BUSINESS_UNIT]) && $virtualProperties[PriceProductDimensionTransfer::ID_BUSINESS_UNIT];
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

        $tierPrices = $this->priceProductFacade->matchTierPrice(
            $priceProductCriteriaTransfer,
            $priceProductStoreEntityTransfer
        );

        if ($tierPrices) {
            return $moneyValueTransfer
                ->setGrossAmount($tierPrices[static::GROSS_PRICE])
                ->setNetAmount($tierPrices[static::NET_PRICE]);
        }

        return $moneyValueTransfer
            ->setNetAmount($priceProductStoreEntityTransfer->getNetPrice())
            ->setGrossAmount($priceProductStoreEntityTransfer->getGrossPrice());
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
