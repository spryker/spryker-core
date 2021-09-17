<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Dependency\Facade;

interface ConfigurableBundleGuiToProductListFacadeInterface
{
    /**
     * @param array<int> $productListIds
     *
     * @return array<int>
     */
    public function getProductConcreteIdsByProductListIds(array $productListIds): array;
}
