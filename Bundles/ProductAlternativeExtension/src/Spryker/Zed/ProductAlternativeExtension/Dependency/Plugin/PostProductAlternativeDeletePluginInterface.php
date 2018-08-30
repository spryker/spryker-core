<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductAlternativeTransfer;

interface PostProductAlternativeDeletePluginInterface
{
    /**
     * Specification:
     *  - Executes after ProductAlternative deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return void
     */
    public function execute(ProductAlternativeTransfer $productAlternativeTransfer): void;
}
