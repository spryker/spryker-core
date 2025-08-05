<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Reader;

use Generated\Shared\Transfer\FileConditionsTransfer;
use Generated\Shared\Transfer\FileCriteriaTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;

class FileReader implements FileReaderInterface
{
    public function __construct(protected FileManagerFacadeInterface $fileManagerFacade)
    {
    }

    public function findFileByIdFile(int $idFile): ?FileTransfer
    {
        $fileConditionsTransfer = (new FileConditionsTransfer())
            ->addIdFile($idFile);

        $fileCriteriaTransfer = (new FileCriteriaTransfer())
            ->setFileConditions($fileConditionsTransfer);

        $fileCollectionTransfer = $this->fileManagerFacade
            ->getFileCollection($fileCriteriaTransfer);

        return $fileCollectionTransfer->getFiles()->getIterator()->current();
    }
}
