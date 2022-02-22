<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Validator;

use Generated\Shared\Transfer\ItemValidationTransfer;

interface PriceProductItemValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $itemValidationTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationTransfer
     */
    public function validate(ItemValidationTransfer $itemValidationTransfer): ItemValidationTransfer;
}
