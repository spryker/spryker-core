<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrderExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ItemValidationResponseTransfer;

interface ItemValidatorPluginInterface
{
    /**
     * Specification:
     * - Validates ItemTransfer.
     * - Returns ItemValidationResponseTransfer with error or warning messages.
     * - In case any fields need to be updated ItemValidationResponseTransfer contains ItemTransfer with 'recommended values transfer' inside.
     * - Returns empty ItemValidationResponseTransfer when no validation errors or warnings.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationResponseTransfer
     */
    public function validate(ItemTransfer $itemTransfer): ItemValidationResponseTransfer;
}
