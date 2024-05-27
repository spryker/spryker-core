<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer;

interface MerchantCommissionImportValidatorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $newMerchantCommissionTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $existingMerchantCommissionTransfers
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer
     */
    public function validate(
        ArrayObject $newMerchantCommissionTransfers,
        ArrayObject $existingMerchantCommissionTransfers
    ): MerchantCommissionCollectionResponseTransfer;
}
