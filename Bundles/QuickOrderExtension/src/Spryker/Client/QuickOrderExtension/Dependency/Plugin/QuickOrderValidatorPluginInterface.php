<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrderExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderValidationResponseTransfer;

interface QuickOrderValidatorPluginInterface
{
    /**
     * Specification:
     * - Validates QuickOrderItemTransfer.
     * - Returns QuickOrderValidationResponseTransfer with error or warning messages.
     * - In case any fields need to be updated QuickOrderValidationResponseTransfer contains 'correct values' array.
     * - Returns empty QuickOrderValidationResponseTransfer when no validation errors or warnings.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderValidationResponseTransfer
     */
    public function validateQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer): QuickOrderValidationResponseTransfer;
}
