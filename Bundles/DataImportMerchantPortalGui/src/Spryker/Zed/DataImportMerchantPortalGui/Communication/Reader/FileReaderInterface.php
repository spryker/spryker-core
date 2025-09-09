<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchantPortalGui\Communication\Reader;

use Generated\Shared\Transfer\DataImportMerchantFileTransfer;

interface FileReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return mixed|resource
     */
    public function read(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer);

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return array<string, string>
     */
    public function getSourceFileResponseHeaders(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return array<string, string>
     */
    public function getErrorsFileResponseHeaders(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): array;
}
