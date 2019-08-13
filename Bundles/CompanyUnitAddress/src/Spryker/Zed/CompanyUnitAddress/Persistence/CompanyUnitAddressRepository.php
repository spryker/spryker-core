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
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
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
     * @deprecated Use `getCompanyBusinessUnitAddressesByCriteriaFilter()` and `getCompanyBusinessUnitAddressToBusinessUnitRelations()` instead.
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    public function getCompanyUnitAddressCollection(
        CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUnitAddressCollectionTransfer {

        $query = $this->getFactory()
            ->createCompanyUnitAddressQuery()
            ->innerJoinWithCountry()
            ->leftJoinWithSpyCompanyUnitAddressToCompanyBusinessUnit()
            ->useSpyCompanyUnitAddressToCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithCompanyBusinessUnit()
            ->endUse();

        if ($criteriaFilterTransfer->getIdCompany() !== null) {
            $query->filterByFkCompany($criteriaFilterTransfer->getIdCompany());
        }

        if ($criteriaFilterTransfer->getIdCompanyBusinessUnit() !== null) {
            $query->useSpyCompanyUnitAddressToCompanyBusinessUnitQuery()
                ->filterByFkCompanyBusinessUnit($criteriaFilterTransfer->getIdCompanyBusinessUnit())
                ->endUse();
        }

        $collection = $this->buildQueryFromCriteria($query, $criteriaFilterTransfer->getFilter());
        /** @var \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer[] $companyUnitAddressEntityTransfers */
        $companyUnitAddressEntityTransfers = $this->getPaginatedCollection($collection, $criteriaFilterTransfer->getPagination());

        $collectionTransfer = new CompanyUnitAddressCollectionTransfer();
        foreach ($companyUnitAddressEntityTransfers as $companyUnitAddressEntityTransfer) {
            $unitAddressTransfer = $this->getFactory()
                ->createCompanyUnitAddressMapper()
                ->mapEntityTransferToCompanyUnitAddressTransfer(
                    $companyUnitAddressEntityTransfer,
                    new CompanyUnitAddressTransfer()
                );

            $collectionTransfer->addCompanyUnitAddress($unitAddressTransfer);
        }

        $collectionTransfer->setPagination($criteriaFilterTransfer->getPagination());

        return $collectionTransfer;
    }

    /**
     * @module Country
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    public function getCompanyBusinessUnitAddressesByCriteriaFilter(
        CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUnitAddressCollectionTransfer {
        $companyUnitAddressQuery = $this->getFactory()
            ->createCompanyUnitAddressQuery()
            ->innerJoinWithCountry();

        if ($criteriaFilterTransfer->getIdCompany() !== null) {
            $companyUnitAddressQuery->filterByFkCompany($criteriaFilterTransfer->getIdCompany());
        }

        if ($criteriaFilterTransfer->getIdCompanyBusinessUnit() !== null) {
            $companyUnitAddressQuery->useSpyCompanyUnitAddressToCompanyBusinessUnitQuery()
                    ->filterByFkCompanyBusinessUnit($criteriaFilterTransfer->getIdCompanyBusinessUnit())
                ->endUse();
        }

        $companyUnitAddressCollection = $this->buildQueryFromCriteria($companyUnitAddressQuery, $criteriaFilterTransfer->getFilter());
        /** @var \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer[] $companyUnitAddressEntityTransfers */
        $companyUnitAddressEntityTransfers = $this->getPaginatedCollection($companyUnitAddressCollection, $criteriaFilterTransfer->getPagination());

        $companyUnitAddressCollectionTransfer = new CompanyUnitAddressCollectionTransfer();
        foreach ($companyUnitAddressEntityTransfers as $companyUnitAddressEntityTransfer) {
            $companyUnitAddressTransfer = $this->getFactory()
                ->createCompanyUnitAddressMapper()
                ->mapEntityTransferToCompanyUnitAddressTransfer(
                    $companyUnitAddressEntityTransfer,
                    new CompanyUnitAddressTransfer()
                );

            $companyUnitAddressCollectionTransfer->addCompanyUnitAddress($companyUnitAddressTransfer);
        }

        $companyUnitAddressCollectionTransfer->setPagination($criteriaFilterTransfer->getPagination());

        return $companyUnitAddressCollectionTransfer;
    }

    /**
     * @module CompanyBusinessUnit
     *
     * @param int[] $companyUnitAddressIds
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer[]
     */
    public function getCompanyBusinessUnitAddressToBusinessUnitRelations(
        array $companyUnitAddressIds
    ): array {
        $companyUnitAddressToCompanyBusinessUnitQuery = $this->getFactory()
            ->createCompanyUnitAddressToCompanyBusinessUnitQuery()
            ->filterByFkCompanyUnitAddress_In(
                $companyUnitAddressIds
            )
            ->leftJoinWithCompanyBusinessUnit();

        $companyBusinessUnitEntity = $this->buildQueryFromCriteria($companyUnitAddressToCompanyBusinessUnitQuery)->find();

        $indexedCompanyBusinessUnitTransfers = $this->getFactory()
            ->createCompanyUnitAddressMapper()
            ->mapEntitiesToCompanyBusinessUnitTransfers($companyBusinessUnitEntity);

        return $indexedCompanyBusinessUnitTransfers;
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
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\Collection|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getPaginatedCollection(ModelCriteria $query, ?PaginationTransfer $paginationTransfer = null)
    {
        if ($paginationTransfer !== null) {
            $page = $paginationTransfer
                ->requirePage()
                ->getPage();

            $maxPerPage = $paginationTransfer
                ->requireMaxPerPage()
                ->getMaxPerPage();

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

        return $query->find();
    }
}
