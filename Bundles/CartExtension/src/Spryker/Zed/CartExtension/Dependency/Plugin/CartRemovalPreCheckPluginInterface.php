<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;

interface CartRemovalPreCheckPluginInterface
{
    /**
     * Specification:
     * - Checks if the provided cart removal CartChangeTransfer fulfills the specified conditions.
     * - Returns CartPreCheckResponseTransfer with isSuccess true when conditions are met and false when they are unfulfilled.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer);
}
