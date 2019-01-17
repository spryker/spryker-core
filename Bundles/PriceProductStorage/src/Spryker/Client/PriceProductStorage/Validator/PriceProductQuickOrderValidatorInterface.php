<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Validator;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderValidationResponseTransfer;

interface PriceProductQuickOrderValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderValidationResponseTransfer
     */
    public function validateQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer): QuickOrderValidationResponseTransfer;
}
