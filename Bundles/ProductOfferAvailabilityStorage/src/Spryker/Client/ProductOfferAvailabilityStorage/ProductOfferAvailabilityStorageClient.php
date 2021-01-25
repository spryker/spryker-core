<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferAvailabilityStorage;

use Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageFactory getFactory()
 */
class ProductOfferAvailabilityStorageClient extends AbstractClient implements ProductOfferAvailabilityStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $productOfferReference
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer|null
     */
    public function findByProductOfferReference(string $productOfferReference, string $storeName): ?ProductOfferAvailabilityStorageTransfer
    {
        return $this->getFactory()
            ->createProductOfferAvailabilityStorageReader()
            ->findByProductOfferReference($productOfferReference, $storeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $productOfferReferences
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer[]
     */
    public function getByProductOfferReferences(array $productOfferReferences, string $storeName): array
    {
        return $this->getFactory()
            ->createProductOfferAvailabilityStorageReader()
            ->getByProductOfferReferences($productOfferReferences, $storeName);
    }
}
