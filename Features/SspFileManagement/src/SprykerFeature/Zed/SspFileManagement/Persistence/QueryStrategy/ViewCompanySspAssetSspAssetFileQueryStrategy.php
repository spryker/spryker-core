<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Persistence\QueryStrategy;

use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Orm\Zed\SspFileManagement\Persistence\SpySspAssetFileQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Adapter\Pdo\PgsqlAdapter;
use Propel\Runtime\Propel;
use SprykerFeature\Shared\SspAssetManagement\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;
use SprykerFeature\Shared\SspFileManagement\SspFileManagementConfig;

class ViewCompanySspAssetSspAssetFileQueryStrategy implements FilePermissionQueryStrategyInterface
{
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
                        ->useSpyCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
                            ->filterByFkCompany(
                                $fileAttachmentFileCriteriaTransfer->getCompanyUserOrFail()->getCompanyBusinessUnitOrFail()->getCompanyOrFail()->getIdCompanyOrFail(),
                            )
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->withColumn('entity_name', FileAttachmentTransfer::ENTITY_NAME)
            ->withColumn('entity_id', FileAttachmentTransfer::ENTITY_ID)
            ->addSelectQuery(
                SpySspAssetFileQuery::create()
                    ->withColumn(Propel::getAdapter() instanceof PgsqlAdapter ? "'ssp_asset'::text" : "'ssp_asset'", 'entity_name')
                    ->withColumn('fk_ssp_asset', 'entity_id'),
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
        return ViewCompanySspAssetPermissionPlugin::class::KEY;
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer): bool
    {
        $entityTypes = $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()->getEntityTypes();

        return in_array(SspFileManagementConfig::ENTITY_TYPE_SSP_ASSET, $entityTypes, true);
    }
}
