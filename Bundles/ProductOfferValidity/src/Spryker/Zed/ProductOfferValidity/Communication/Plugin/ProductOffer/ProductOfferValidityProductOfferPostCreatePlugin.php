<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidity\Communication\Plugin\ProductOffer;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferPostCreatePluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferValidity\ProductOfferValidityConfig getConfig()
 * @method \Spryker\Zed\ProductOfferValidity\Business\ProductOfferValidityFacadeInterface getFacade()
 */
class ProductOfferValidityProductOfferPostCreatePlugin extends AbstractPlugin implements ProductOfferPostCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Persists new Product Offer Validity entity to database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function execute(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $productOfferValidityTransfer = $productOfferTransfer->getProductOfferValidity();

        if (!$productOfferValidityTransfer) {
            return $productOfferTransfer;
        }

        $productOfferValidityTransfer->setIdProductOffer($productOfferTransfer->getIdProductOffer());
        $productOfferValidityTransfer = $this->getFacade()->create($productOfferValidityTransfer);

        return $productOfferTransfer->setProductOfferValidity($productOfferValidityTransfer);
    }
}
