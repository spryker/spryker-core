<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\MerchantFileCollectionTransfer;
use Generated\Shared\Transfer\MerchantFileCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileResultTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;

interface FileImportMerchantPortalGuiToMerchantFileFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileResultTransfer
     */
    public function writeMerchantFile(MerchantFileTransfer $merchantFileTransfer): MerchantFileResultTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer|null
     */
    public function findMerchantFile(MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer): ?MerchantFileTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileCollectionTransfer
     */
    public function getMerchantFileCollection(
        MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
    ): MerchantFileCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return resource
     */
    public function readMerchantFileStream(MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer);
}
