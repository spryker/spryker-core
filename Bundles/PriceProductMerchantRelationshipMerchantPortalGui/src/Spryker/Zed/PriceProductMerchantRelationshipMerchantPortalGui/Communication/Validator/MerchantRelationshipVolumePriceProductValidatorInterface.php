<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ValidationResponseTransfer;

interface MerchantRelationshipVolumePriceProductValidatorInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validate(ArrayObject $priceProductTransfers): ValidationResponseTransfer;
}
