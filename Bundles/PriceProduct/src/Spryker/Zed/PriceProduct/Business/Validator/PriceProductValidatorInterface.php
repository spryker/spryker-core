<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Validator;

use Generated\Shared\Transfer\PriceProductCollectionTransfer;
use Generated\Shared\Transfer\PriceProductCollectionValidationResponseTransfer;

interface PriceProductValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductCollectionTransfer $priceProductCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCollectionValidationResponseTransfer
     */
    public function validatePrices(PriceProductCollectionTransfer $priceProductCollectionTransfer): PriceProductCollectionValidationResponseTransfer;
}
