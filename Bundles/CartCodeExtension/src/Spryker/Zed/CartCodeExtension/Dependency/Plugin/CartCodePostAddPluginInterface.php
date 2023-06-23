<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCodeExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\CartCodeResponseTransfer;

/**
 * Provides extension capabilities to perform additional operations after a cart code was added and processed.
 *
 * This plugin interface allows you to perform additional operations or apply business logic after a cart code
 * is added and processed. Implement this interface in your plugin class to extend the functionality of the
 * Cart Code feature and make adjustments after processing and the recalcuation of quote.
 */
interface CartCodePostAddPluginInterface
{
    /**
     * Specification:
     * - Executed after a cart code is added and processed to perform additional operations.
     * - Updates CartCodeResponseTransfer.isSuccessful to false in case of error otherwise to true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function execute(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer;
}
