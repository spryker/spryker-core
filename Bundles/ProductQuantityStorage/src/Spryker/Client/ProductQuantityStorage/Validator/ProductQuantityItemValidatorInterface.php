<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Validator;

use Generated\Shared\Transfer\ItemValidationTransfer;

interface ProductQuantityItemValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $ItemValidationTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationTransfer
     */
    public function validate(ItemValidationTransfer $ItemValidationTransfer): ItemValidationTransfer;
}
