<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\QueryBuilder;

use ArrayObject;
use Generated\Shared\Transfer\FileAttachmentConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\FileManager\Persistence\Map\SpyFileInfoTableMap;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpyCompanyBusinessUnitFileTableMap;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpyCompanyUserFileTableMap;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetFileTableMap;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetTableMap;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspModelToFileTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class FileAttachmentQueryBuilder
{
    /**
     * @var string
     */
    public const BUSINESS_UNIT_IDS_COLUMN = 'businessUnitIds';

    /**
     * @var string
     */
    public const COMPANY_IDS_COLUMN = 'companyIds';

    /**
     * @var string
     */
    public const COMPANY_USER_IDS_COLUMN = 'companyUserIds';

    /**
     * @var string
     */
    public const SSP_ASSET_IDS_COLUMN = 'sspAssetIds';

    /**
     * @var string
     */
    public const SSP_MODEL_IDS_COLUMN = 'sspModelIds';

    public function applyCriteria(SpyFileQuery $fileQuery, FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer): SpyFileQuery
    {
        $fileQuery = $this->applyFileIdFilter($fileQuery, $fileAttachmentCriteriaTransfer);
        $fileQuery = $this->applyFileAttachmentUuidFilter($fileQuery, $fileAttachmentCriteriaTransfer);
        $fileQuery = $this->addRelationsToAQuery($fileQuery, $fileAttachmentCriteriaTransfer);
        $fileQuery = $this->applyFileAttachmentSearch($fileQuery, $fileAttachmentCriteriaTransfer);
        $fileQuery = $this->applyFileAttachmentTypeFilter($fileQuery, $fileAttachmentCriteriaTransfer);
        $fileQuery = $this->applyFileAttachmentDateRangeFilter($fileQuery, $fileAttachmentCriteriaTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers */
        $sortTransfers = $fileAttachmentCriteriaTransfer->getSortCollection();
        $this->applyFileAttachmentSorting($fileQuery, $sortTransfers);

        return $fileQuery;
    }

    protected function addRelationsToAQuery(SpyFileQuery $fileQuery, FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer): SpyFileQuery
    {
        if (
            !$fileAttachmentCriteriaTransfer->getWithBusinessUnitRelation() &&
            !$fileAttachmentCriteriaTransfer->getWithCompanyUserRelation() &&
            !$fileAttachmentCriteriaTransfer->getWithSspAssetRelation() &&
            !$fileAttachmentCriteriaTransfer->getWithSspModelRelation()
        ) {
            return $fileQuery;
        }

        $fileAttachmentConditions = $fileAttachmentCriteriaTransfer->getFileAttachmentConditions() ?? new FileAttachmentConditionsTransfer();

        $fileAttachmentFilterCriteria = null;
        if ($fileAttachmentCriteriaTransfer->getWithBusinessUnitRelation()) {
            $fileQuery
                ->withColumn('GROUP_CONCAT(' . SpyCompanyBusinessUnitFileTableMap::COL_FK_COMPANY_BUSINESS_UNIT . ')', static::BUSINESS_UNIT_IDS_COLUMN)
                ->joinSpyCompanyBusinessUnitFile(null, Criteria::LEFT_JOIN);

            $businessUnitFileCriteria = $this->getBusinessUnitFileCriteria($fileQuery, $fileAttachmentConditions);

            $fileAttachmentFilterCriteria = $this->addSubCriteria($businessUnitFileCriteria, $fileAttachmentFilterCriteria);
        }

        if ($fileAttachmentCriteriaTransfer->getWithCompanyUserRelation()) {
            $fileQuery
                ->withColumn('GROUP_CONCAT(' . SpyCompanyUserFileTableMap::COL_FK_COMPANY_USER . ')', static::COMPANY_USER_IDS_COLUMN)
                ->joinSpyCompanyUserFile(null, Criteria::LEFT_JOIN);

            $companyUserFilterCriteria = $this->getCompanyUserFileCriteria($fileQuery, $fileAttachmentConditions);

            if ($companyUserFilterCriteria) {
                $fileAttachmentFilterCriteria = $this->addSubCriteria($companyUserFilterCriteria, $fileAttachmentFilterCriteria);
            }
        }

        if ($fileAttachmentCriteriaTransfer->getWithSspAssetRelation()) {
            $fileQuery
                ->withColumn('GROUP_CONCAT(' . SpySspAssetFileTableMap::COL_FK_SSP_ASSET . ')', static::SSP_ASSET_IDS_COLUMN)
                ->useSpySspAssetFileQuery(null, Criteria::LEFT_JOIN);

            $sspAssetFilterCriteria = $this->applySspAssetFileCriteria($fileQuery, $fileAttachmentCriteriaTransfer);

            if ($sspAssetFilterCriteria) {
                $fileAttachmentFilterCriteria = $this->addSubCriteria($sspAssetFilterCriteria, $fileAttachmentFilterCriteria);
            }
        }

        if ($fileAttachmentCriteriaTransfer->getWithSspModelRelation()) {
            $fileQuery
                ->withColumn('GROUP_CONCAT(' . SpySspModelToFileTableMap::COL_FK_SSP_MODEL . ')', static::SSP_MODEL_IDS_COLUMN)
                ->joinSpySspModelToFile(null, Criteria::LEFT_JOIN);

            $sspModelFilterCriteria = $this->getSspModelToFileCriteria($fileQuery, $fileAttachmentConditions);

            if ($sspModelFilterCriteria) {
                $fileAttachmentFilterCriteria = $this->addSubCriteria($sspModelFilterCriteria, $fileAttachmentFilterCriteria);
            }
        }

        if (!$fileAttachmentFilterCriteria) {
            return $fileQuery;
        }

        $fileQuery->addAnd($fileAttachmentFilterCriteria);

        return $fileQuery;
    }

    protected function getBusinessUnitFileCriteria(
        SpyFileQuery $fileQuery,
        FileAttachmentConditionsTransfer $fileAttachmentConditionsTransfer
    ): ?AbstractCriterion {
        $businessUnitQuery = $fileQuery
            ->useSpyCompanyBusinessUnitFileQuery(null, Criteria::LEFT_JOIN)
            ->useCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN);

        $businessUnitQuery->endUse()->endUse();

        $criteria = null;
        if ($fileAttachmentConditionsTransfer->getCompanyIds()) {
            $criteria = $businessUnitQuery->getNewCriterion(
                SpyCompanyBusinessUnitTableMap::COL_FK_COMPANY,
                $fileAttachmentConditionsTransfer->getCompanyIds(),
                Criteria::IN,
            );
        }

        if ($fileAttachmentConditionsTransfer->getBusinessUnitIds()) {
            $businessUnitIdFileCriteria = $businessUnitQuery->getNewCriterion(
                SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT,
                $fileAttachmentConditionsTransfer->getBusinessUnitIds(),
                Criteria::IN,
            );

            if ($criteria) {
                $criteria->addAnd($businessUnitIdFileCriteria);

                return $criteria;
            }

            return $businessUnitIdFileCriteria;
        }

        if ($fileAttachmentConditionsTransfer->getBusinessUnitUuids()) {
            $businessUnitUuidCriteria = $businessUnitQuery->getNewCriterion(
                SpyCompanyBusinessUnitTableMap::COL_UUID,
                $fileAttachmentConditionsTransfer->getBusinessUnitUuids(),
                Criteria::IN,
            );

            if ($criteria) {
                $criteria->addAnd($businessUnitUuidCriteria);

                return $criteria;
            }
        }

        return $criteria;
    }

    protected function getCompanyUserFileCriteria(
        SpyFileQuery $fileQuery,
        FileAttachmentConditionsTransfer $fileAttachmentConditionsTransfer
    ): ?AbstractCriterion {
        $companyUserFileQuery = $fileQuery
            ->useSpyCompanyUserFileQuery(null, Criteria::LEFT_JOIN);

        $companyUserQuery = $companyUserFileQuery->useCompanyUserQuery('company_user', Criteria::LEFT_JOIN);

        $companyUserFileQuery->endUse();

        $criteria = null;
        if ($fileAttachmentConditionsTransfer->getCompanyUserIds()) {
            $criteria = $companyUserFileQuery->getNewCriterion(
                SpyCompanyUserFileTableMap::COL_FK_COMPANY_USER,
                $fileAttachmentConditionsTransfer->getCompanyUserIds(),
                Criteria::IN,
            );
        }

        if ($fileAttachmentConditionsTransfer->getCompanyIds()) {
            $companyIdCriteria = $companyUserQuery->getNewCriterion(
                'company_user.fk_company',
                $fileAttachmentConditionsTransfer->getCompanyIds(),
                Criteria::IN,
            );

            if ($criteria) {
                $companyIdCriteria->addAnd($criteria);
            }

            if (!$criteria) {
                $criteria = $companyIdCriteria;
            }
        }

        if ($fileAttachmentConditionsTransfer->getBusinessUnitIds()) {
            $businessUnitIdCriteria = $companyUserQuery->getNewCriterion(
                'company_user.fk_company_business_unit',
                $fileAttachmentConditionsTransfer->getBusinessUnitIds(),
                Criteria::IN,
            );

            if ($criteria) {
                $criteria->addAnd($businessUnitIdCriteria);

                return $criteria;
            }

            return $businessUnitIdCriteria;
        }

        return $criteria;
    }

    protected function applySspAssetFileCriteria(SpyFileQuery $fileQuery, FileAttachmentCriteriaTransfer $criteriaTransfer): ?AbstractCriterion
    {
        $fileAttachmentCriteriaTransfer = $criteriaTransfer->getFileAttachmentConditionsOrFail();

        $sspAssetQuery = $fileQuery
            ->useSpySspAssetFileQuery(null, Criteria::LEFT_JOIN)
            ->useSspAssetQuery(null, Criteria::LEFT_JOIN);

        $companyBusinessUnitQuery = $sspAssetQuery
            ->useSpySspAssetToCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
            ->joinSpyCompanyBusinessUnit('ssp_asset_business_unit', Criteria::LEFT_JOIN);

        $companyBusinessUnitQuery
            ->endUse()
            ->endUse()
            ->endUse();

        $criteria = null;
        if ($fileAttachmentCriteriaTransfer->getAssetReferences()) {
            $criteria = $sspAssetQuery->getNewCriterion(
                SpySspAssetTableMap::COL_REFERENCE,
                $fileAttachmentCriteriaTransfer->getAssetReferences(),
                Criteria::IN,
            );
        }

        if ($fileAttachmentCriteriaTransfer->getSspAssetCompanyIds()) {
            $assetCompanyIdCriterion = $fileQuery->getNewCriterion(
                'ssp_asset_business_unit.fk_company',
                $fileAttachmentCriteriaTransfer->getSspAssetCompanyIds(),
                Criteria::IN,
            );

            if ($criteria) {
                $criteria->addAnd($assetCompanyIdCriterion);
            } else {
                $criteria = $assetCompanyIdCriterion;
            }
        }

        if ($fileAttachmentCriteriaTransfer->getSspAssetBusinessUnitIds()) {
            $assetBusinessUnitIdCriterion = $fileQuery->getNewCriterion(
                'ssp_asset_business_unit.id_company_business_unit',
                $fileAttachmentCriteriaTransfer->getSspAssetBusinessUnitIds(),
                Criteria::IN,
            );

            if ($criteria) {
                $criteria->addAnd($assetBusinessUnitIdCriterion);
            } else {
                $criteria = $assetBusinessUnitIdCriterion;
            }
        }

        return $criteria;
    }

    protected function applyFileAttachmentSearch(
        SpyFileQuery $query,
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): SpyFileQuery {
        if (!$fileAttachmentCriteriaTransfer->getFileAttachmentSearchConditions()) {
            return $query;
        }

        $searchString = $fileAttachmentCriteriaTransfer->getFileAttachmentSearchConditions()->getSearchString();
        if ($searchString) {
            $query->filterByFileName_Like(sprintf('%%%s%%', $searchString))
                ->_or()
                ->filterByFileReference_Like(sprintf('%%%s%%', $searchString));
        }

        return $query;
    }

    protected function applyFileAttachmentTypeFilter(
        SpyFileQuery $query,
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): SpyFileQuery {
        $fileTypes = $fileAttachmentCriteriaTransfer->getFileAttachmentConditionsOrFail()->getFileTypes();

        if (!$fileTypes) {
            return $query;
        }

        return $query
            ->useSpyFileInfoQuery()
            ->filterByExtension_In($fileTypes)
            ->endUse();
    }

    protected function applyFileAttachmentDateRangeFilter(
        SpyFileQuery $query,
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): SpyFileQuery {
        $rangeCreatedAt = $fileAttachmentCriteriaTransfer->getFileAttachmentConditionsOrFail()->getRangeCreatedAt();
        if (!$rangeCreatedAt) {
            return $query;
        }

        if ($rangeCreatedAt->getFrom()) {
            $query->useSpyFileInfoQuery()
                ->filterByCreatedAt($rangeCreatedAt->getFrom(), Criteria::GREATER_EQUAL)
                ->endUse();
        }

        if ($rangeCreatedAt->getTo()) {
            $query->useSpyFileInfoQuery()
                ->filterByCreatedAt($rangeCreatedAt->getTo(), Criteria::LESS_EQUAL)
                ->endUse();
        }

        return $query;
    }

    protected function applyFileAttachmentUuidFilter(
        SpyFileQuery $query,
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): SpyFileQuery {
        $uuids = $fileAttachmentCriteriaTransfer->getFileAttachmentConditionsOrFail()->getUuids();
        if ($uuids !== []) {
            $query
                ->filterByUuid_In($uuids);
        }

        return $query;
    }

    protected function applyFileIdFilter(
        SpyFileQuery $query,
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): SpyFileQuery {
        $fileIds = $fileAttachmentCriteriaTransfer->getFileAttachmentConditionsOrFail()->getIdFiles();
        if ($fileIds !== []) {
            $query
                ->filterByIdFile_In($fileIds);
        }

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyFileAttachmentSorting(ModelCriteria $query, ArrayObject $sortTransfers): ModelCriteria
    {
        $fileAttachmentSortFieldMapping = $this->getFileAttachmentSortFieldMapping();
        foreach ($sortTransfers as $sortTransfer) {
            $query
                ->groupBy($fileAttachmentSortFieldMapping[$sortTransfer->getFieldOrFail()] ?? $sortTransfer->getFieldOrFail())
                ->orderBy(
                    $fileAttachmentSortFieldMapping[$sortTransfer->getFieldOrFail()] ?? $sortTransfer->getFieldOrFail(),
                    $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
                );
        }

        return $query;
    }

    /**
     * @return array<string, string>
     */
    protected function getFileAttachmentSortFieldMapping(): array
    {
        return [
            'fileType' => SpyFileInfoTableMap::COL_EXTENSION,
            'size' => SpyFileInfoTableMap::COL_SIZE,
            'createdAt' => SpyFileInfoTableMap::COL_CREATED_AT,
        ];
    }

    protected function getSspModelToFileCriteria(
        SpyFileQuery $fileQuery,
        FileAttachmentConditionsTransfer $fileAttachmentConditionsTransfer
    ): ?AbstractCriterion {
        $sspModelToFileQuery = $fileQuery
            ->useSpySspModelToFileQuery(null, Criteria::LEFT_JOIN);

        if (!$fileAttachmentConditionsTransfer->getSspModelIds()) {
            return null;
        }

        return $sspModelToFileQuery->getNewCriterion(
            SpySspModelToFileTableMap::COL_FK_SSP_MODEL,
            $fileAttachmentConditionsTransfer->getSspModelIds(),
            Criteria::IN,
        );
    }

    protected function addSubCriteria(?AbstractCriterion $subCriteria, ?AbstractCriterion $criteria): ?AbstractCriterion
    {
        if (!$subCriteria) {
            return $criteria;
        }

        if ($criteria) {
            $criteria->addOr($subCriteria);

            return $criteria;
        }

        return $subCriteria;
    }
}
