<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferAvailabilitiesRestApi\Dependency\Client;

interface ProductOfferAvailabilitiesRestApiToProductOfferAvailabilityStorageClientInterface
{
    /**
     * @param array<string> $productOfferReferences
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer>
     */
    public function getByProductOfferReferences(array $productOfferReferences, string $storeName): array;
}
