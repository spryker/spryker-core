<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Saver;

use Generated\Shared\Transfer\FileAttachmentTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFile;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig as SharedSelfServicePortalConfig;

class CompanyFileSaver implements FileAttachmentSaverInterface
{
    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery $companyFileQuery
     */
    public function __construct(protected SpyCompanyFileQuery $companyFileQuery)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return bool
     */
    public function isApplicable(FileAttachmentTransfer $fileAttachmentTransfer): bool
    {
        return $fileAttachmentTransfer->getEntityNameOrFail() === SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentTransfer
     */
    public function save(FileAttachmentTransfer $fileAttachmentTransfer): FileAttachmentTransfer
    {
        $companyFileEntity = $this->companyFileQuery
            ->filterByFkCompany($fileAttachmentTransfer->getEntityIdOrFail())
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
