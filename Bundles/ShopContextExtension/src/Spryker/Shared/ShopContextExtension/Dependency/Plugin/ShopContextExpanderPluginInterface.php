<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ShopContextExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ShopContextTransfer;

interface ShopContextExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands and returns the provided ShopContext transfer objects.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShopContextTransfer $shopContextTransfer
     *
     * @return \Generated\Shared\Transfer\ShopContextTransfer
     */
    public function expand(ShopContextTransfer $shopContextTransfer): ShopContextTransfer;
}
