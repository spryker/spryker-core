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
     * - Validates ItemValidationTransfer::item property.
     * - Returns unchanged ItemValidationTransfer if ItemValidationTransfer::item property is valid.
     * - Sets ItemValidationTransfer::messages and ItemValidationTransfer::suggestedValues otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $itemValidationTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationTransfer
     */
    public function validate(ItemValidationTransfer $itemValidationTransfer): ItemValidationTransfer;
}
