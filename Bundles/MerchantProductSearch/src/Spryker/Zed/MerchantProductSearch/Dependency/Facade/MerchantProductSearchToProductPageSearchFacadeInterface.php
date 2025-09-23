<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Dependency\Facade;

interface MerchantProductSearchToProductPageSearchFacadeInterface
{
    /**
     * @param array<int> $productAbstractIds
     * @param array $pageDataExpanderPluginNames
     *
     * @return void
     */
    public function refresh(array $productAbstractIds, $pageDataExpanderPluginNames = []);

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds);
}
