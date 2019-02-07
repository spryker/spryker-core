<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrderExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ItemValidationTransfer;

interface ItemValidatorPluginInterface
{
    /**
     * Specification:
     * - Checks if product concrete provided in ItemValidationTransfer has price or not.
     * - If price is not found adds error message to ItemValidationTransfer.
     * - In case if some fields need to be updated ItemValidationTransfer contains recommendedValues with ItemValidationTransfer inside.
     * - Returns empty ItemValidationTransfer if price for product is exists.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $ItemValidationTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationTransfer
     */
    public function validate(ItemValidationTransfer $ItemValidationTransfer): ItemValidationTransfer;
}
