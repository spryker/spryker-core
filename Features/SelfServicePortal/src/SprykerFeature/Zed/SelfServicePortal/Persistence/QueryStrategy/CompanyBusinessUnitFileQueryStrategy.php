<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\QueryStrategy;

use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyBusinessUnitFilesPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig as SharedSelfServicePortalConfig;

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
        $query
            ->_or()
            ->useSpyCompanyBusinessUnitFileQuery(null, Criteria::LEFT_JOIN)
                ->useCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
                    ->useCompanyUserQuery(static::COMPANY_USER_RELATION_ALIAS, Criteria::LEFT_JOIN)
                        ->filterByIdCompanyUser($fileAttachmentFileCriteriaTransfer->getCompanyUserOrFail()->getIdCompanyUserOrFail())
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

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer): bool
    {
        $entityTypes = $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()->getEntityTypes();

        return in_array(SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT, $entityTypes, true);
    }
}
