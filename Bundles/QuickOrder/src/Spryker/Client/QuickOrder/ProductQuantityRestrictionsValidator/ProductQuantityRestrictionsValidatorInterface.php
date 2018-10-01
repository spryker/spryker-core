<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\ProductQuantityRestrictionsValidator;

use Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;

interface ProductQuantityRestrictionsValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer
     */
    public function validateQuantityRestrictions(QuickOrderItemTransfer $quickOrderItemTransfer): ProductQuantityValidationResponseTransfer;
}
