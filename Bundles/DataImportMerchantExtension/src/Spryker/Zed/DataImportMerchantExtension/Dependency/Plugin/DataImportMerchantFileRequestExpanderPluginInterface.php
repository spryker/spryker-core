<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer;

/**
 * Use this plugin interface to expand data import merchant file request before validation.
 */
interface DataImportMerchantFileRequestExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands data import merchant file collection request.
     * - Executed before validation and file processing.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer
     */
    public function expand(
        DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
    ): DataImportMerchantFileCollectionRequestTransfer;
}
