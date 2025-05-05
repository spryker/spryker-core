<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Persistence\QueryStrategy;

use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Orm\Zed\SspFileManagement\Persistence\Map\SpySspAssetFileTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use SprykerFeature\Shared\SspAssetManagement\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SspAssetManagement\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;
use SprykerFeature\Shared\SspFileManagement\SspFileManagementConfig;

class ViewBusinessUnitSspAssetSspAssetFileQueryStrategy implements FilePermissionQueryStrategyInterface
{
    use PermissionAwareTrait;

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $query
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function apply(SpyFileQuery $query, FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer): SpyFileQuery
    {
        $sspAssetQuery = $query
            ->_or()
            ->useSpySspAssetFileQuery(null, Criteria::LEFT_JOIN)
                ->useSspAssetQuery(null, Criteria::LEFT_JOIN);

        if ($fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()->getAssetReferences()) {
            $sspAssetQuery->filterByReference_In($fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()->getAssetReferences());
        }
        $sspAssetQuery->useSpySspAssetToCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
                        ->useSpyCompanyBusinessUnitQuery('ssp_asset_company_business_unit', Criteria::LEFT_JOIN)
                            ->filterByIdCompanyBusinessUnit(
                                $fileAttachmentFileCriteriaTransfer->getCompanyUserOrFail()->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail(),
                            )
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->addSelectQuery(
                (new Criteria())
                ->addSelectColumn("'ssp_asset' AS " . FileAttachmentTransfer::ENTITY_NAME)
                ->addSelectColumn(SpySspAssetFileTableMap::COL_FK_SSP_ASSET . ' AS ' . FileAttachmentTransfer::ENTITY_ID),
                'ssp_asset_query',
                false,
            );

        return $query;
    }

    /**
     * @return string
     */
    public function getPermissionKey(): string
    {
        return ViewBusinessUnitSspAssetPermissionPlugin::class::KEY;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer): bool
    {
        $entityTypes = $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()->getEntityTypes();

        if (!in_array(SspFileManagementConfig::ENTITY_TYPE_SSP_ASSET, $entityTypes, true)) {
            return false;
        }

        return !$this->can(ViewCompanySspAssetPermissionPlugin::class::KEY, $fileAttachmentFileCriteriaTransfer->getCompanyUserOrFail()->getIdCompanyUserOrFail());
    }
}
