<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ValidationResponseTransfer;

interface PriceProductValidatorInterface
{
    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validatePrices(ArrayObject $priceProductTransfers): ValidationResponseTransfer;
}
