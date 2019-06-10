<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Resolver;

interface ProductQuantityResolverInterface
{
    /**
     * @param int $idProduct
     * @param float $quantity
     *
     * @return float
     */
    public function getNearestQuantity(int $idProduct, float $quantity): float;
}
