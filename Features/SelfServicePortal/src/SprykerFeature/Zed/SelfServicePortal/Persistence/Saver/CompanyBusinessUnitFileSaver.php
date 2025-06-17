<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Saver;

use Generated\Shared\Transfer\FileAttachmentTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFile;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig as SharedSelfServicePortalConfig;

class CompanyBusinessUnitFileSaver implements FileAttachmentSaverInterface
{
    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery $companyBusinessUnitFileQuery
     */
    public function __construct(protected SpyCompanyBusinessUnitFileQuery $companyBusinessUnitFileQuery)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return bool
     */
    public function isApplicable(FileAttachmentTransfer $fileAttachmentTransfer): bool
    {
        return $fileAttachmentTransfer->getEntityNameOrFail() === SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT;
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
