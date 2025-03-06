<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Persistence\Saver;

use Generated\Shared\Transfer\FileAttachmentTransfer;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFile;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery;

class CompanyBusinessUnitFileSaver implements FileAttachmentSaverInterface
{
    /**
     * @param \Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery $companyBusinessUnitFileQuery
     */
    public function __construct(protected SpyCompanyBusinessUnitFileQuery $companyBusinessUnitFileQuery)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentTransfer
     */
    public function save(FileAttachmentTransfer $fileAttachmentTransfer): FileAttachmentTransfer
    {
        $companyBusinessUnitFileEntity = $this->companyBusinessUnitFileQuery
            ->filterByIdCompanyBusinessUnitFile($fileAttachmentTransfer->getEntityIdOrFail())
            ->findOne();

        if ($companyBusinessUnitFileEntity === null) {
            $companyBusinessUnitFileEntity = new SpyCompanyBusinessUnitFile();
        }

        $companyBusinessUnitFileEntity->setFkCompanyBusinessUnit($fileAttachmentTransfer->getEntityIdOrFail());
        $companyBusinessUnitFileEntity->setFkFile($fileAttachmentTransfer->getFileOrFail()->getIdFileOrFail());
        $companyBusinessUnitFileEntity->save();

        $fileAttachmentTransfer->setEntityId($companyBusinessUnitFileEntity->getIdCompanyBusinessUnitFile());

        return $fileAttachmentTransfer;
    }
}
