<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin;

interface ProductConcreteDiscontinuedCheckPluginInterface
{
    /**
     * Specification:
     * - Executed before adding of label "Alternatives available" to product.
     * - Checks if concrete products are discontinued.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return bool
     */
    public function checkConcreteProductDiscontinued(int $idProduct): bool;
}
