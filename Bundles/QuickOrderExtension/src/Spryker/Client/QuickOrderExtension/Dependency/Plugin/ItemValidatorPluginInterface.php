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
     * - Checks if product concrete provided in ItemTransfer has price or not.
     * - If price is not found adds error message to ItemValidationResponseTransfer.
     * - In case if some fields need to be updated ItemValidationResponseTransfer contains recommendedValues with ItemTransfer inside.
     * - Returns empty ItemValidationResponseTransfer if price for product is exists.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationResponseTransfer
     */
    public function validate(ItemTransfer $itemTransfer): ItemValidationResponseTransfer;
}
