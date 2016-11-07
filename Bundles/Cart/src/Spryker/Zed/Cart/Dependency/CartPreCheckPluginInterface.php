<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Dependency;

use Generated\Shared\Transfer\CartChangeTransfer;

interface CartPreCheckPluginInterface
{

    /**
     *
     * Specification:
     * - This plugin is executed before cart add operation is executed,
     *   for example could be used to check if item quantity is available for selected item
     *   Should return CartPreCheckResponseTransfer where error messages set and flag that check failed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer);

}
