<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Saver;

use Generated\Shared\Transfer\FileAttachmentTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFile;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig as SharedSelfServicePortalConfig;

class CompanyUserFileSaver implements FileAttachmentSaverInterface
{
    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery $companyUserFileQuery
     */
    public function __construct(protected SpyCompanyUserFileQuery $companyUserFileQuery)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return bool
     */
    public function isApplicable(FileAttachmentTransfer $fileAttachmentTransfer): bool
    {
        return $fileAttachmentTransfer->getEntityNameOrFail() === SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_USER;
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
