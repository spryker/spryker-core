<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Dependency\Facade;

interface ProductDiscontinuedToProductFacadeInterface
{
    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function deactivateProductConcrete($idProductConcrete): void;

    /**
     * @param string[] $productConcreteSkus
     *
     * @return void
     */
    public function deactivateProductConcretesByConcreteSkus(array $productConcreteSkus): void;
}
