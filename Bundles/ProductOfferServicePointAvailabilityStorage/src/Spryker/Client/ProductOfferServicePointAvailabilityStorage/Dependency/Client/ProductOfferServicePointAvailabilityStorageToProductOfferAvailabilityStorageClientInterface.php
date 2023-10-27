<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityStorage\Dependency\Client;

interface ProductOfferServicePointAvailabilityStorageToProductOfferAvailabilityStorageClientInterface
{
    /**
     * @param list<string> $productOfferReferences
     * @param string $storeName
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer>
     */
    public function getByProductOfferReferences(array $productOfferReferences, string $storeName): array;
}
