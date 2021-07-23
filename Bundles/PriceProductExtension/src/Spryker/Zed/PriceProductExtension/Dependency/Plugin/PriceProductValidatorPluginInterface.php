<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductExtension\Dependency\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\ValidationResponseTransfer;

/**
 * Provides additional validation rules.
 */
interface PriceProductValidatorPluginInterface
{
    /**
     * Specification:
     * - Provides additional validation by a given collection of `PriceProduct` transfer objects.
     * - Returns `ValidationResponse` transfer object.
     *
     * @api
     *
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validate(ArrayObject $priceProductTransfers): ValidationResponseTransfer;
}
