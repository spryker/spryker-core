<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferCollectionValidationResponseTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface getRepository()
 * @method \Spryker\Zed\PriceProductOffer\Business\PriceProductOfferBusinessFactory getFactory()
 * @method \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferEntityManagerInterface getEntityManager()
 */
class PriceProductOfferFacade extends AbstractFacade implements PriceProductOfferFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function saveProductOfferPrices(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        return $this->getFactory()->createPriceProductOfferWriter()->saveProductOfferPrices($productOfferTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function savePriceProductOfferRelation(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        return $this->getFactory()->createPriceProductOfferWriter()->savePriceProductOfferRelation($priceProductTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function expandProductOfferWithPrices(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        return $this->getFactory()->createProductOfferExpander()->expandProductOfferWithPrices($productOfferTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferCollectionValidationResponseTransfer
     */
    public function validateProductOfferPrices(ArrayObject $priceProductTransfers): PriceProductOfferCollectionValidationResponseTransfer
    {
        return $this->getFactory()
            ->createPriceProductOfferValidator()
            ->validateProductOfferPrices($priceProductTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     *
     * @return void
     */
    public function deleteProductOfferPrices(PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer): void
    {
        $this->getFactory()->createPriceProductOfferDeleter()->deleteProductOfferPrices($priceProductOfferCollectionTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return int
     */
    public function count(PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer): int
    {
        return $this->getRepository()->count($priceProductOfferCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getProductOfferPrices(PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer): ArrayObject
    {
        return $this->getRepository()->getProductOfferPrices($priceProductOfferCriteriaTransfer);
    }
}
