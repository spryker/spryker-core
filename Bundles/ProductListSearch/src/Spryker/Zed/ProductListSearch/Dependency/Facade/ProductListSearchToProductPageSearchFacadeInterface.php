<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Dependency\Facade;

interface ProductListSearchToProductPageSearchFacadeInterface
{
    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds): void;

    /**
     * @param array<int> $productAbstractIds
     * @param array<string> $pageDataExpanderPluginNames
     *
     * @return void
     */
    public function refresh(array $productAbstractIds, array $pageDataExpanderPluginNames = []): void;

    /**
     * @param array<int> $productConcreteIds
     *
     * @return void
     */
    public function publishProductConcretes(array $productConcreteIds): void;
}
