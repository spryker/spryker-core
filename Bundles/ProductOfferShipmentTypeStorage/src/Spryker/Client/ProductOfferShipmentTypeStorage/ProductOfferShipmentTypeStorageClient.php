<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShipmentTypeStorage;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageFactory getFactory()
 */
class ProductOfferShipmentTypeStorageClient extends AbstractClient implements ProductOfferShipmentTypeStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function expandProductOfferStorageWithShipmentTypes(ProductOfferStorageTransfer $productOfferStorageTransfer): ProductOfferStorageTransfer
    {
        return $this
            ->getFactory()
            ->createProductOfferStorageExpander()
            ->expandProductOfferStorageWithShipmentTypes($productOfferStorageTransfer);
    }
}
