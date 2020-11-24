<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductOfferCollectionValidationResponseTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;

class ProductOfferMerchantPortalGuiToPriceProductOfferFacadeBridge implements ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface
{
    /**
     * @var \Spryker\Zed\PriceProductOffer\Business\PriceProductOfferFacadeInterface
     */
    protected $priceProductOfferFacade;

    /**
     * @param \Spryker\Zed\PriceProductOffer\Business\PriceProductOfferFacadeInterface $priceProductOfferFacade
     */
    public function __construct($priceProductOfferFacade)
    {
        $this->priceProductOfferFacade = $priceProductOfferFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function saveProductOfferPrices(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        return $this->priceProductOfferFacade->saveProductOfferPrices($productOfferTransfer);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductOfferTransfer[] $productOfferTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferCollectionValidationResponseTransfer
     */
    public function validateProductOfferPrices(ArrayObject $productOfferTransfers): PriceProductOfferCollectionValidationResponseTransfer
    {
        return $this->priceProductOfferFacade->validateProductOfferPrices($productOfferTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return void
     */
    public function delete(PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer): void
    {
        $this->priceProductOfferFacade->delete($priceProductOfferCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return int
     */
    public function count(PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer): int
    {
        return $this->priceProductOfferFacade->count($priceProductOfferCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getProductOfferPrices(PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer): ArrayObject
    {
        return $this->priceProductOfferFacade->getProductOfferPrices($priceProductOfferCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function savePriceProductOfferRelation(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        return $this->priceProductOfferFacade->savePriceProductOfferRelation($priceProductTransfer);
    }
}
