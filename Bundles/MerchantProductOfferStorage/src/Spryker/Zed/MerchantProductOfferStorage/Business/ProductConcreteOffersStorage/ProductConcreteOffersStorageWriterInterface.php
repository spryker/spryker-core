<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\ProductConcreteOffersStorage;

interface ProductConcreteOffersStorageWriterInterface
{
    /**
     * @param string[] $concreteSkus
     *
     * @return void
     */
    public function publish(array $concreteSkus): void;

    /**
     * @param string[] $concreteSkus
     *
     * @return void
     */
    public function unpublish(array $concreteSkus): void;
}
