<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin;

interface ProductApplicableLabelAlternativePluginInterface
{
    /**
     * Specification:
     * - Executed before adding of label "Alternatives available" to product.
     * - Checks if concrete product is applicable for adding of label "Alternatives available".
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return bool
     */
    public function check(int $idProduct): bool;
}
