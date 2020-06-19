<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\Storage;

interface ProductOfferStorageKeyGeneratorInterface
{
    /**
     * @param string[] $productConcreteSkus
     *
     * @return string[]
     */
    public function generateProductConcreteProductOffersKeys(array $productConcreteSkus): array;

    /**
     * @param string[] $merchantProductOfferReferences
     *
     * @return string[]
     */
    public function generateMerchantProductOfferKeys(array $merchantProductOfferReferences): array;

    /**
     * @param string $keyName
     * @param string $resourceName
     *
     * @return string
     */
    public function generateKey(string $keyName, string $resourceName): string;
}
