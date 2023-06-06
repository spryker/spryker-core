<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeEntityManagerInterface getEntityManager()
 */
class ProductOfferShipmentTypeFacade extends AbstractFacade implements ProductOfferShipmentTypeFacadeInterface
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
    public function expandProductOfferWithShipmentTypes(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        return $this->getFactory()
            ->createProductOfferExpander()
            ->expandProductOfferWithShipmentTypes($productOfferTransfer);
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
    public function createProductOfferShipmentTypes(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        return $this->getFactory()
            ->createProductOfferShipmentTypeCreator()
            ->createProductOfferShipmentTypes($productOfferTransfer);
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
    public function updateProductOfferShipmentTypes(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        return $this->getFactory()
            ->createProductOfferShipmentTypeUpdater()
            ->updateProductOfferShipmentTypes($productOfferTransfer);
    }
}
