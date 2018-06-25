<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade;


interface ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface
{
    /**
     * @param int[] $productIds
     *
     * @return bool
     */
    public function doAllConcreteProductsHaveAlternatives(array $productIds): bool;
}
