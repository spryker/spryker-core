<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferStorage\Storage;

interface ProductOfferStorageKeyGeneratorInterface
{
    /**
     * @param array<string> $productConcreteSkus
     *
     * @return array<string>
     */
    public function generateProductConcreteProductOffersKeys(array $productConcreteSkus): array;

    /**
     * @param array<string> $productOfferReferences
     *
     * @return array<string>
     */
    public function generateProductOfferKeys(array $productOfferReferences): array;

    /**
     * @param string $keyName
     * @param string $resourceName
     *
     * @return string
     */
    public function generateKey(string $keyName, string $resourceName): string;
}
