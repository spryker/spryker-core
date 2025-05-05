<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Persistence\QueryStrategy;

use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Shared\SspFileManagement\Plugin\Permission\ViewCompanyUserFilesPermissionPlugin;
use SprykerFeature\Shared\SspFileManagement\SspFileManagementConfig;

class CompanyUserFileQueryStrategy implements FilePermissionQueryStrategyInterface
{
    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $query
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function apply(SpyFileQuery $query, FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer): SpyFileQuery
    {
        $idCompanyUser = $fileAttachmentFileCriteriaTransfer->getCompanyUserOrFail()->getIdCompanyUser();

        $query
            ->_or()
            ->useSpyCompanyUserFileQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkCompanyUser($idCompanyUser)
            ->endUse();

        return $query;
    }

    /**
     * @return string
     */
    public function getPermissionKey(): string
    {
        return ViewCompanyUserFilesPermissionPlugin::KEY;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer): bool
    {
        $entityTypes = $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()->getEntityTypes();

        return in_array(SspFileManagementConfig::ENTITY_TYPE_COMPANY_USER, $entityTypes, true);
    }
}
