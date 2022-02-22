<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin;

interface AlternativeProductApplicablePluginInterface
{
    /**
     * Specification:
     *  - Checks if product alternatives should be shown for product concrete.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return bool
     */
    public function check(int $idProduct): bool;
}
