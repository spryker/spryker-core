<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartChangeItemValidationResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;

interface CartChangeItemValidatorPluginInterface
{
    /**
     * Specification:
     * - Validates ItemTransfer.
     * - Returns CartChangeItemValidationResponseTransfer with error messages.
     * - In case any fields need to be updated CartChangeItemValidationResponseTransfer contains 'correct values' array.
     * - Returns empty CartChangeItemValidationResponseTransfer when no validation errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeItemValidationResponseTransfer
     */
    public function validateItemTransfer(ItemTransfer $quickOrderItemTransfer): CartChangeItemValidationResponseTransfer;
}
