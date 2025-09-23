<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Business\Validator;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;

interface DataImportMerchantFileValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer
     */
    public function validate(
        DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
    ): DataImportMerchantFileCollectionResponseTransfer;
}
