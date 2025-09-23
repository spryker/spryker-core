<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer;

/**
 * Implement this plugin to provide additional data for data import merchant files once they are retrieved from the persistence.
 */
interface DataImportMerchantFileExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands data import merchant file collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer $dataImportMerchantFileCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer
     */
    public function expand(
        DataImportMerchantFileCollectionTransfer $dataImportMerchantFileCollectionTransfer
    ): DataImportMerchantFileCollectionTransfer;
}
