<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage;

interface MerchantProductOfferStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves content by key through a storage client dependency.
     * - Returns the product offer collection.
     *
     * @api
     *
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer[]
     */
    public function getProductOfferStorageCollection(string $concreteSku): array;
}
