<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointStorage\Generator;

interface ProductOfferServiceStorageKeyGeneratorInterface
{
    /**
     * @param list<string> $productOfferReferences
     * @param string $storeName
     *
     * @return list<string>
     */
    public function generateKeys(array $productOfferReferences, string $storeName): array;
}
