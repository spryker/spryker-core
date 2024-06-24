<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OrderTransfer;

/**
 * Provides extension capabilities for orders after they were canceled.
 */
interface OrderPostCancelPluginInterface
{
    /**
     * Specification:
     * - This plugin stack gets executed after order was canceled.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function postCancel(OrderTransfer $orderTransfer): OrderTransfer;
}
