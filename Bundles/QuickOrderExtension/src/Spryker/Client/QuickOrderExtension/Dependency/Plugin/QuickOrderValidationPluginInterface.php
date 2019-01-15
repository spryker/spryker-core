<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrderExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuickOrderItemTransfer;

interface QuickOrderValidationPluginInterface
{
    /**
     * Specification:
     * - Validates QuickOrderItemTransfer items.
     * - Executes when processing UploadOrder form, add specific error message to the QuickOrderItem transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    public function validateQuickOrderItemProduct(QuickOrderItemTransfer $quickOrderItemTransfer): QuickOrderItemTransfer;
}
