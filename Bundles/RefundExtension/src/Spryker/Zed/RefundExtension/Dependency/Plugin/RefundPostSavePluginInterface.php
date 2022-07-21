<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RefundExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;

/**
 * Use this plugin if you need additional things to be done after the refund is saved.
 */
interface RefundPostSavePluginInterface
{
    /**
     * Specification:
     * - Runs after order refund is created.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function postSave(RefundTransfer $refundTransfer, OrderTransfer $orderTransfer): RefundTransfer;
}
