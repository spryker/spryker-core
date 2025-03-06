<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Persistence\QueryStrategy;

use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Shared\SspFileManagement\Plugin\Permission\ViewCompanyBusinessUnitFilesPermissionPlugin;
use SprykerFeature\Shared\SspFileManagement\SspFileManagementConfig;

class CompanyBusinessUnitFileQueryStrategy implements FilePermissionQueryStrategyInterface
{
    /**
     * @var string
     */
    protected const COMPANY_USER_RELATION_ALIAS = 'bucu';

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $query
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function apply(SpyFileQuery $query, FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer): SpyFileQuery
    {
        $entityTypes = $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()->getEntityTypes();

        if ($entityTypes !== [] && !in_array(SspFileManagementConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT, $entityTypes, true)) {
            return $query;
        }

        $idCompanyUser = $fileAttachmentFileCriteriaTransfer->getCompanyUserOrFail()->getIdCompanyUser();

        $query
            ->_or()
            ->useSpyCompanyBusinessUnitFileQuery(null, Criteria::LEFT_JOIN)
                ->useCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
                    ->useCompanyUserQuery(static::COMPANY_USER_RELATION_ALIAS, Criteria::LEFT_JOIN)
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
        return ViewCompanyBusinessUnitFilesPermissionPlugin::KEY;
    }
}
