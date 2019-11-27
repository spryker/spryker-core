<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\ProductConcreteOffersStorage;

interface ProductConcreteOffersStorageWriterInterface
{
    /**
     * @param string[] $productSkus
     *
     * @return void
     */
    public function publish(array $productSkus): void;

    /**
     * @param string[] $productSkus
     *
     * @return void
     */
    public function unpublish(array $productSkus): void;
}
