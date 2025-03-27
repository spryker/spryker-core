<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Persistence;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SspAssetAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Orm\Zed\SspAssetManagement\Persistence\Map\SpySspAssetTableMap;
use Orm\Zed\SspAssetManagement\Persistence\SpySspAssetQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementPersistenceFactory getFactory()
 */
class SspAssetManagementRepository extends AbstractRepository implements SspAssetManagementRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    public function getSspAssetCollection(SspAssetCriteriaTransfer $sspAssetCriteriaTransfer): SspAssetCollectionTransfer
    {
        $sspAssetCollectionTransfer = new SspAssetCollectionTransfer();

        $sspAssetQuery = $this->getFactory()->createSspAssetQuery();

        $sspAssetQuery = $this->applyAssetConditions($sspAssetQuery, $sspAssetCriteriaTransfer);
        $sspAssetQuery = $this->applyAssetSorting($sspAssetQuery, $sspAssetCriteriaTransfer);

        if ($sspAssetCriteriaTransfer->getInclude()?->getWithCompanyBusinessUnit()) {
            $sspAssetQuery->joinWithSpyCompanyBusinessUnit(Criteria::LEFT_JOIN);
        }

        $sspAssetEntities = $this->getPaginatedCollection($sspAssetQuery, $sspAssetCriteriaTransfer->getPagination());
        $sspAssetIds = [];
        foreach ($sspAssetEntities as $sspAssetEntity) {
            $sspAssetTransfer = $this->getFactory()
                ->createAssetMapper()
                ->mapSpySspAssetEntityToSspAssetTransfer($sspAssetEntity, new SspAssetTransfer());

            if ($sspAssetCriteriaTransfer->getInclude()) {
                $sspAssetTransfer = $this->getFactory()
                    ->createAssetMapper()
                    ->mapSpySspAssetEntityToSspAssetTransferIncludes(
                        $sspAssetEntity,
                        $sspAssetTransfer,
                        $sspAssetCriteriaTransfer->getInclude(),
                    );
            }

            $sspAssetCollectionTransfer->addSspAsset($sspAssetTransfer);
            $sspAssetIds[] = $sspAssetTransfer->getIdSspAsset();
        }

        $sspAssetToCompanyBusinessUnitQuery = $this->getFactory()->createSspAssetToCompanyBusinessUnitQuery()
            ->filterByFkSspAsset_In($sspAssetIds)
            ->joinWithSpyCompanyBusinessUnit();

        if ($sspAssetCriteriaTransfer->getSspAssetConditions()) {
            if ($sspAssetCriteriaTransfer->getSspAssetConditions()->getAssignedBusinessUnitId()) {
                $sspAssetToCompanyBusinessUnitQuery->filterByFkCompanyBusinessUnit(
                    $sspAssetCriteriaTransfer->getSspAssetConditions()->getAssignedBusinessUnitId(),
                );
            }

            if ($sspAssetCriteriaTransfer->getSspAssetConditions()->getAssignedBusinessUnitCompanyId()) {
                $sspAssetToCompanyBusinessUnitQuery
                    ->useSpyCompanyBusinessUnitQuery()
                        ->filterByFkCompany($sspAssetCriteriaTransfer->getSspAssetConditions()->getAssignedBusinessUnitCompanyId())
                    ->endUse();
            }
        }

        $sspAssetToCompanyBusinessUnitEntities = $sspAssetToCompanyBusinessUnitQuery->find();

        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            foreach ($sspAssetToCompanyBusinessUnitEntities as $sspAssetToCompanyBusinessUnit) {
                if ($sspAssetToCompanyBusinessUnit->getFkSspAsset() === $sspAssetTransfer->getIdSspAsset()) {
                    $sspAssetTransfer->addAssignment(
                        (new SspAssetAssignmentTransfer())
                            ->setCompanyBusinessUnit(
                                (new CompanyBusinessUnitTransfer())
                                    ->setIdCompanyBusinessUnit($sspAssetToCompanyBusinessUnit->getFkCompanyBusinessUnit())
                                    ->setName($sspAssetToCompanyBusinessUnit->getSpyCompanyBusinessUnit()->getName())
                                    ->setFkCompany($sspAssetToCompanyBusinessUnit->getSpyCompanyBusinessUnit()->getFkCompany()),
                            )
                            ->setAssignedAt($sspAssetToCompanyBusinessUnit->getCreatedAt()->format('Y-m-d H:i:s')),
                    );
                }
            }
        }

        $sspAssetCollectionTransfer->setPagination($sspAssetCriteriaTransfer->getPagination());

        return $sspAssetCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\SspAssetManagement\Persistence\SpySspAssetQuery $sspAssetQuery
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Orm\Zed\SspAssetManagement\Persistence\SpySspAssetQuery
     */
    protected function applyAssetConditions(
        SpySspAssetQuery $sspAssetQuery,
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
    ): SpySspAssetQuery {
        $sspAssetConditionsTransfer = $sspAssetCriteriaTransfer->getSspAssetConditions();

        if (!$sspAssetConditionsTransfer) {
            return $sspAssetQuery;
        }

        if ($sspAssetConditionsTransfer->getSspAssetIds()) {
            $sspAssetQuery->filterByIdSspAsset_In($sspAssetConditionsTransfer->getSspAssetIds());
        }

        if ($sspAssetConditionsTransfer->getReferences()) {
            $sspAssetQuery->filterByReference_In($sspAssetConditionsTransfer->getReferences());
        }

        if ($sspAssetConditionsTransfer->getStatus()) {
            $sspAssetQuery->filterByStatus($sspAssetConditionsTransfer->getStatus());
        }

        if ($sspAssetConditionsTransfer->getAssignedBusinessUnitId()) {
            $sspAssetQuery
                ->useSpySspAssetToCompanyBusinessUnitExistsQuery()
                    ->filterByFkCompanyBusinessUnit($sspAssetConditionsTransfer->getAssignedBusinessUnitId())
                ->endUse();
        }

        if ($sspAssetConditionsTransfer->getAssignedBusinessUnitCompanyId()) {
            $sspAssetQuery
                ->useSpySspAssetToCompanyBusinessUnitExistsQuery()
                    ->useSpyCompanyBusinessUnitQuery()
                        ->filterByFkCompany($sspAssetConditionsTransfer->getAssignedBusinessUnitCompanyId())
                    ->endUse()
                ->endUse();
        }

        if ($sspAssetConditionsTransfer->getSearchText()) {
            $searchText = '%' . $sspAssetConditionsTransfer->getSearchText() . '%';
            $sspAssetQuery->where(
                '(spy_ssp_asset.name LIKE ? OR spy_ssp_asset.reference LIKE ? OR spy_ssp_asset.serial_number LIKE ?)',
                [$searchText, $searchText, $searchText],
            );
        }

        if ($sspAssetConditionsTransfer->getFileIds() !== []) {
            $sspAssetQuery->filterByFkImageFile_In($sspAssetConditionsTransfer->getFileIds());
        }

        return $sspAssetQuery;
    }

    /**
     * @param \Orm\Zed\SspAssetManagement\Persistence\SpySspAssetQuery $sspAssetQuery
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Orm\Zed\SspAssetManagement\Persistence\SpySspAssetQuery
     */
    protected function applyAssetSorting(
        SpySspAssetQuery $sspAssetQuery,
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
    ): SpySspAssetQuery {
        $sortCollection = $sspAssetCriteriaTransfer->getSortCollection();

        if (!$sortCollection->count()) {
            return $sspAssetQuery;
        }

        foreach ($sortCollection as $sort) {
            $field = $sort->getFieldOrFail();
            if ($field === SspAssetTransfer::CREATED_DATE) {
                $field = SpySspAssetTableMap::COL_CREATED_AT;
            }
            $direction = $sort->getIsAscending() ? Criteria::ASC : Criteria::DESC;
            $sspAssetQuery->orderBy($field, $direction);
        }

        return $sspAssetQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Propel\Runtime\Collection\Collection
     */
    protected function getPaginatedCollection(ModelCriteria $query, ?PaginationTransfer $paginationTransfer = null): Collection
    {
        if ($paginationTransfer === null) {
            return $query->find();
        }

        $page = $paginationTransfer
            ->requirePage()
            ->getPageOrFail();

        $maxPerPage = $paginationTransfer
            ->requireMaxPerPage()
            ->getMaxPerPageOrFail();

        $paginationModel = $query->paginate($page, $maxPerPage);

        $paginationTransfer->setNbResults($paginationModel->getNbResults());
        $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
        $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
        $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
        $paginationTransfer->setLastPage($paginationModel->getLastPage());
        $paginationTransfer->setNextPage($paginationModel->getNextPage());
        $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

        return $paginationModel->getResults();
    }
}
