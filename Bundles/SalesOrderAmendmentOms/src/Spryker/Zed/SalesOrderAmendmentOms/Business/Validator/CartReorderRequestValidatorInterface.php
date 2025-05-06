<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Validator;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderResponseTransfer;

interface CartReorderRequestValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function validate(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        CartReorderResponseTransfer $cartReorderResponseTransfer
    ): CartReorderResponseTransfer;
}
