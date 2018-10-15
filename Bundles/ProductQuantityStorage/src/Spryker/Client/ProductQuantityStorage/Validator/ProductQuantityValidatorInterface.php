<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Validator;

use Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductQuantityValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer
     */
    public function validate(ProductViewTransfer $productViewTransfer): ProductQuantityValidationResponseTransfer;
}
