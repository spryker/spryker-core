<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade;

interface ProductAlternativeProductLabelConnectorToAvailabilityFacadeInterface
{
    /**
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function isProductConcreteIsAvailable(int $idProductConcrete): bool;

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function calculateStockForProduct(string $sku): bool;
}
