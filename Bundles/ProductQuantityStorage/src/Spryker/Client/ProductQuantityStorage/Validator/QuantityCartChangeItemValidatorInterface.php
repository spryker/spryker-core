<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Validator;

use Generated\Shared\Transfer\CartChangeItemValidationResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;

interface QuantityCartChangeItemValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeItemValidationResponseTransfer
     */
    public function validateCartChangeItem(ItemTransfer $itemTransfer): CartChangeItemValidationResponseTransfer;
}
