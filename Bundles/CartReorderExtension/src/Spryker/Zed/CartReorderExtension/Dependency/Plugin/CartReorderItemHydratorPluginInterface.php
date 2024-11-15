<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartReorderExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartReorderTransfer;

/**
 * Use this plugin interface to hydrate reorder items.
 */
interface CartReorderItemHydratorPluginInterface
{
    /**
     * Specification:
     * - Hydrates reorder items.
     * - Returns the hydrated reorder items in `CartReorderTransfer.reorderItems`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrate(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer;
}
