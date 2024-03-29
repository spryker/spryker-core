<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Communication\Plugin\ProductOffer;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferServicePoint\ProductOfferServicePointConfig getConfig()
 * @method \Spryker\Zed\ProductOfferServicePoint\Business\ProductOfferServicePointFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferServicePoint\Communication\ProductOfferServicePointCommunicationFactory getFactory()
 */
class ServiceProductOfferExpanderPlugin extends AbstractPlugin implements ProductOfferExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ProductOfferTransfer.idProductOffer` to be set.
     * - Expands `ProductOfferTransfer.services` with services from persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function expand(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $productOfferCollectionTransfer = $this->getFacade()->expandProductOfferCollectionWithServices(
            (new ProductOfferCollectionTransfer())->addProductOffer($productOfferTransfer),
        );

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers */
        $productOfferTransfers = $productOfferCollectionTransfer->getProductOffers();

        return $productOfferTransfers->getIterator()->current();
    }
}
