<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Persistence\Saver;

use Generated\Shared\Transfer\FileAttachmentTransfer;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyFile;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery;

class CompanyFileSaver implements FileAttachmentSaverInterface
{
    /**
     * @param \Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery $companyFileQuery
     */
    public function __construct(protected SpyCompanyFileQuery $companyFileQuery)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentTransfer
     */
    public function save(FileAttachmentTransfer $fileAttachmentTransfer): FileAttachmentTransfer
    {
        $companyFileEntity = $this->companyFileQuery
            ->filterByIdCompanyFile($fileAttachmentTransfer->getEntityIdOrFail())
            ->findOne();

        if ($companyFileEntity === null) {
            $companyFileEntity = new SpyCompanyFile();
        }

        $companyFileEntity->setFkCompany($fileAttachmentTransfer->getEntityIdOrFail());
        $companyFileEntity->setFkFile($fileAttachmentTransfer->getFileOrFail()->getIdFileOrFail());
        $companyFileEntity->save();

        $fileAttachmentTransfer->setEntityId($companyFileEntity->getIdCompanyFile());

        return $fileAttachmentTransfer;
    }
}
