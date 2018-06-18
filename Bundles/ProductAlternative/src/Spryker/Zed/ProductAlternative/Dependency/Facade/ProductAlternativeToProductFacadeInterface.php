<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Dependency\Facade;

interface ProductAlternativeToProductFacadeInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductActive($idProductAbstract);

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductAbstractIdBySku($sku);

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductConcreteIdBySku($sku);
}
