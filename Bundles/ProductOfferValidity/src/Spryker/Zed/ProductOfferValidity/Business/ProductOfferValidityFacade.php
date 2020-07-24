<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidity\Business;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ProductOfferValidityTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOfferValidity\Business\ProductOfferValidityBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductOfferValidity\Persistence\ProductOfferValidityRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferValidity\Persistence\ProductOfferValidityEntityManagerInterface getEntityManager()
 */
class ProductOfferValidityFacade extends AbstractFacade implements ProductOfferValidityFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function updateProductOfferStatusByValidityDate(): void
    {
        $this->getFactory()
            ->createProductOfferSwitcher()
            ->updateProductOfferValidity();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferValidityTransfer $productOfferValidityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferValidityTransfer
     */
    public function create(ProductOfferValidityTransfer $productOfferValidityTransfer): ProductOfferValidityTransfer
    {
        return $this->getEntityManager()->create($productOfferValidityTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferValidityTransfer $productOfferValidityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferValidityTransfer
     */
    public function update(ProductOfferValidityTransfer $productOfferValidityTransfer): ProductOfferValidityTransfer
    {
        return $this->getEntityManager()->update($productOfferValidityTransfer);
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
    public function expandProductOfferWithProductOfferValidity(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        return $this->getFactory()
            ->createProductOfferExpander()
            ->expandProductOfferWithProductOfferValidity($productOfferTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductOffer
     *
     * @return \Generated\Shared\Transfer\ProductOfferValidityTransfer|null
     */
    public function findProductOfferValidityByIdProductOffer(int $idProductOffer): ?ProductOfferValidityTransfer
    {
        return $this->getRepository()->findProductOfferValidityByIdProductOffer($idProductOffer);
    }
}
