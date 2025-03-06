<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Persistence\QueryStrategy;

use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Shared\SspFileManagement\Plugin\Permission\ViewCompanyFilesPermissionPlugin;
use SprykerFeature\Shared\SspFileManagement\SspFileManagementConfig;

class CompanyFileQueryStrategy implements FilePermissionQueryStrategyInterface
{
    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $query
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function apply(SpyFileQuery $query, FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer): SpyFileQuery
    {
        $entityTypes = $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()->getEntityTypes();

        if ($entityTypes !== [] && !in_array(SspFileManagementConfig::ENTITY_TYPE_COMPANY, $entityTypes, true)) {
            return $query;
        }

        $idCompanyUser = $fileAttachmentFileCriteriaTransfer->getCompanyUserOrFail()->getIdCompanyUser();

        $query
            ->_or()
            ->useSpyCompanyFileQuery(null, Criteria::LEFT_JOIN)
                ->useCompanyQuery(null, Criteria::LEFT_JOIN)
                    ->useCompanyUserQuery(null, Criteria::LEFT_JOIN)
                        ->filterByIdCompanyUser($idCompanyUser)
                    ->endUse()
                ->endUse()
            ->endUse();

        return $query;
    }

    /**
     * @return string
     */
    public function getPermissionKey(): string
    {
        return ViewCompanyFilesPermissionPlugin::KEY;
    }
}
