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
     * - Validates ItemTransfer inside ItemValidationTransfer.
     * - Returns not changed ItemValidationTransfer if ItemTransfer is valid.
     * - Returns ItemValidationTransfer with messages and suggestedValues (optional) in case if ItemTransfer is not valid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $itemValidationTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationTransfer
     */
    public function validate(ItemValidationTransfer $itemValidationTransfer): ItemValidationTransfer;
}
