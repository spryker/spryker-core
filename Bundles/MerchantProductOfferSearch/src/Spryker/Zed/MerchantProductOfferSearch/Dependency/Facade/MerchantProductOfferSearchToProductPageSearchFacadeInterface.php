<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Dependency\Facade;

interface MerchantProductOfferSearchToProductPageSearchFacadeInterface
{
    /**
     * @param array<int> $productAbstractIds
     * @param array $pageDataExpanderPluginNames
     *
     * @return void
     */
    public function refresh(array $productAbstractIds, $pageDataExpanderPluginNames = []): void;

    /**
     * @param array<int> $productIds
     *
     * @return void
     */
    public function publishProductConcretes(array $productIds): void;
}
