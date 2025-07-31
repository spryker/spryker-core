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

class FileImportMerchantPortalGuiToMerchantFileFacadeBridge implements FileImportMerchantPortalGuiToMerchantFileFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantFile\Business\MerchantFileFacadeInterface
     */
    protected $merchantFileFacade;

    /**
     * @param \Spryker\Zed\MerchantFile\Business\MerchantFileFacadeInterface $MerchantFileFacade
     */
    public function __construct($MerchantFileFacade)
    {
        $this->merchantFileFacade = $MerchantFileFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileResultTransfer
     */
    public function writeMerchantFile(MerchantFileTransfer $merchantFileTransfer): MerchantFileResultTransfer
    {
        return $this->merchantFileFacade->writeMerchantFile($merchantFileTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer|null
     */
    public function findMerchantFile(MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer): ?MerchantFileTransfer
    {
        return $this->merchantFileFacade->findMerchantFile($merchantFileCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileCollectionTransfer
     */
    public function getMerchantFileCollection(
        MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
    ): MerchantFileCollectionTransfer {
        return $this->merchantFileFacade->getMerchantFileCollection($merchantFileCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return resource
     */
    public function readMerchantFileStream(MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer)
    {
        return $this->merchantFileFacade->readMerchantFileStream($merchantFileCriteriaTransfer);
    }
}
