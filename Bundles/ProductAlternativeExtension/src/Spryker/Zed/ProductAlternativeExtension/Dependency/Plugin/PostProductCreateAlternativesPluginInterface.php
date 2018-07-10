<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductAlternativeTransfer;

interface PostProductCreateAlternativesPluginInterface
{
    /**
     * Specification:
     * - Extends product alternative change request
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function execute(ProductAlternativeTransfer $productAlternativeTransfer);
}
