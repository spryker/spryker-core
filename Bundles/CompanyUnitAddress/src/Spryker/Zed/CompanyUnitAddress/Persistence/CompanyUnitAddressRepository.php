<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence;

use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\CompanyUnitAddress\Persistence\Map\SpyCompanyUnitAddressTableMap;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressPersistenceFactory getFactory()
 */
class CompanyUnitAddressRepository extends AbstractRepository implements CompanyUnitAddressRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function getCompanyUnitAddressById(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressTransfer {
        $companyUnitAddressTransfer->requireIdCompanyUnitAddress();
        $query = $this->getFactory()
            ->createCompanyUnitAddressQuery()
            ->filterByIdCompanyUnitAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress())
            ->innerJoinWithCountry()
            ->leftJoinWithSpyCompanyUnitAddressToCompanyBusinessUnit()
            ->useSpyCompanyUnitAddressToCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithCompanyBusinessUnit()
            ->endUse();

        $entityTransfer = $this->buildQueryFromCriteria($query)->find();

        return $this->getFactory()
            ->createCompanyUnitAddressMapper()
            ->mapEntityTransferToCompanyUnitAddressTransfer($entityTransfer[0], $companyUnitAddressTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    public function getCompanyUnitAddressCollection(
        CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUnitAddressCollectionTransfer {
        $companyUnitAddressQuery = $this->getFactory()
            ->createCompanyUnitAddressQuery();

        if ($criteriaFilterTransfer->getPagination()) {
            $companyUnitAddressQuery->filterByIdCompanyUnitAddress_In(
                $this->getPaginatedCompanyUnitAddressIds($companyUnitAddressQuery, $criteriaFilterTransfer->getPagination())
            );
        }

        $companyUnitAddressQuery = $this->setCompanyUnitAddressQueryFilters($companyUnitAddressQuery, $criteriaFilterTransfer);

        $companyUnitAddressQuery->leftJoinWithCountry()
            ->leftJoinWithSpyCompanyUnitAddressToCompanyBusinessUnit()
            ->useSpyCompanyUnitAddressToCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
            ->leftJoinWithCompanyBusinessUnit()
            ->endUse();

        $companyUnitAddressEntityTransfers = $this->buildQueryFromCriteria($companyUnitAddressQuery, $criteriaFilterTransfer->getFilter())->find();

        $companyUnitAddressCollectionTransfer = $this->getFactory()
            ->createCompanyUnitAddressMapper()
            ->mapCompanyUnitAddressEntityTransfersToCompanyUnitAddressCollectionTransfer(
                $companyUnitAddressEntityTransfers
            );

        $companyUnitAddressCollectionTransfer->setPagination($criteriaFilterTransfer->getPagination());

        return $companyUnitAddressCollectionTransfer;
    }

    /**
     * @module Country
     * @module CompanyBusinessUnit
     *
     * @param int $idCompanyUnitAddress
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer|null
     */
    public function findCompanyUnitAddressById(int $idCompanyUnitAddress): ?CompanyUnitAddressTransfer
    {
        $companyUnitAddressQuery = $this->getFactory()
            ->createCompanyUnitAddressQuery()
            ->filterByIdCompanyUnitAddress($idCompanyUnitAddress)
            ->leftJoinWithCountry()
            ->useSpyCompanyUnitAddressToCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithCompanyBusinessUnit()
            ->endUse();

        /**
         * @var \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress|null
         */
        $companyUnitAddressEntity = $companyUnitAddressQuery->findOne();

        if (!$companyUnitAddressEntity) {
            return null;
        }

        return $this->getFactory()
            ->createCompanyUnitAddressMapper()
            ->mapCompanyUnitAddressEntityToCompanyUnitAddressTransfer($companyUnitAddressEntity, new CompanyUnitAddressTransfer());
    }

    /**
     * @module CompanyBusinessUnit
     * @module Country
     *
     * @param string $companyBusinessUnitAddressUuid
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer|null
     */
    public function findCompanyBusinessUnitAddressByUuid(string $companyBusinessUnitAddressUuid): ?CompanyUnitAddressTransfer
    {
        /** @var \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress|null $companyUnitAddressEntity */
        $companyUnitAddressEntity = $this->getFactory()
            ->createCompanyUnitAddressQuery()
            ->filterByUuid($companyBusinessUnitAddressUuid)
            ->leftJoinWithCountry()
            ->useSpyCompanyUnitAddressToCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinCompanyBusinessUnit()
            ->endUse()
            ->findOne();

        if (!$companyUnitAddressEntity) {
            return null;
        }

        return $this->getFactory()
            ->createCompanyUnitAddressMapper()
            ->mapCompanyUnitAddressEntityToCompanyUnitAddressTransfer(
                $companyUnitAddressEntity,
                new CompanyUnitAddressTransfer()
            );
    }

    /**
     * @param \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery $companyUnitAddressQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return int[]
     */
    protected function getPaginatedCompanyUnitAddressIds(
        SpyCompanyUnitAddressQuery $companyUnitAddressQuery,
        PaginationTransfer $paginationTransfer
    ): array {
        $paginationTransfer->requirePage()
            ->requireMaxPerPage();

        $propelModelPager = $companyUnitAddressQuery->paginate($paginationTransfer->getPage(), $paginationTransfer->getMaxPerPage());

        $paginationTransfer->setNbResults($propelModelPager->getNbResults())
            ->setFirstIndex($propelModelPager->getFirstIndex())
            ->setLastIndex($propelModelPager->getLastIndex())
            ->setFirstPage($propelModelPager->getFirstPage())
            ->setLastPage($propelModelPager->getLastPage())
            ->setNextPage($propelModelPager->getNextPage())
            ->setPreviousPage($propelModelPager->getPreviousPage());

        return $propelModelPager->getQuery()
            ->select(SpyCompanyUnitAddressTableMap::COL_ID_COMPANY_UNIT_ADDRESS)
            ->find()
            ->toArray();
    }

    /**
     * @param \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery $companyUnitAddressQuery
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer $companyUnitAddressCriteriaFilterTransfer
     *
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    protected function setCompanyUnitAddressQueryFilters(
        SpyCompanyUnitAddressQuery $companyUnitAddressQuery,
        CompanyUnitAddressCriteriaFilterTransfer $companyUnitAddressCriteriaFilterTransfer
    ): SpyCompanyUnitAddressQuery {
        if ($companyUnitAddressCriteriaFilterTransfer->getIdCompany()) {
            $companyUnitAddressQuery->filterByFkCompany(
                $companyUnitAddressCriteriaFilterTransfer->getIdCompany()
            );
        }

        if ($companyUnitAddressCriteriaFilterTransfer->getIdCompanyBusinessUnit()) {
            $companyUnitAddressQuery->useSpyCompanyUnitAddressToCompanyBusinessUnitQuery()
                ->filterByFkCompanyBusinessUnit(
                    $companyUnitAddressCriteriaFilterTransfer->getIdCompanyBusinessUnit()
                )
                ->endUse();
        }

        return $companyUnitAddressQuery;
    }
}
