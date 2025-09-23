<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;

/**
 * Use this plugin interface to validate data import merchant files that executes after standard validators.
 */
interface DataImportMerchantFileValidatorPluginInterface
{
    /**
     * Specification:
     * - Validates data import merchant file collection.
     * - Executed after standard validators.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer
     */
    public function validate(
        DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
    ): DataImportMerchantFileCollectionResponseTransfer;
}
