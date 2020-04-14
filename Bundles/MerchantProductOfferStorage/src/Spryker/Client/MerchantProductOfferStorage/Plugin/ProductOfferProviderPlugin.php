<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\Plugin;

use Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferProviderPluginInterface;

class ProductOfferProviderPlugin implements ProductOfferProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns first product offer reference.
     *
     * @api
     *
     * @param string[] $productOfferReferences
     *
     * @return string|null
     */
    public function provideDefaultProductOfferReference(array $productOfferReferences): ?string
    {
        return reset($productOfferReferences);
    }
}
