<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Persistence\Saver;

use Generated\Shared\Transfer\FileAttachmentTransfer;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFile;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery;

class CompanyUserFileSaver implements FileAttachmentSaverInterface
{
    /**
     * @param \Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery $companyUserFileQuery
     */
    public function __construct(protected SpyCompanyUserFileQuery $companyUserFileQuery)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentTransfer
     */
    public function save(FileAttachmentTransfer $fileAttachmentTransfer): FileAttachmentTransfer
    {
        $companyUserFileEntity = $this->companyUserFileQuery
            ->filterByIdCompanyUserFile($fileAttachmentTransfer->getEntityIdOrFail())
            ->findOne();

        if ($companyUserFileEntity === null) {
            $companyUserFileEntity = new SpyCompanyUserFile();
        }

        $companyUserFileEntity->setFkCompanyUser($fileAttachmentTransfer->getEntityIdOrFail());
        $companyUserFileEntity->setFkFile($fileAttachmentTransfer->getFileOrFail()->getIdFileOrFail());
        $companyUserFileEntity->save();

        $fileAttachmentTransfer->setEntityId($companyUserFileEntity->getIdCompanyUserFile());

        return $fileAttachmentTransfer;
    }
}
