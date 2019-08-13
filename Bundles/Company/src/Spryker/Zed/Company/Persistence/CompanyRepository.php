<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\CompanyCollectionTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Company\Persistence\CompanyPersistenceFactory getFactory()
 */
class CompanyRepository extends AbstractRepository implements CompanyRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @param int $idCompany
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getRelatedStoresByCompanyId(int $idCompany)
    {
        $companyStoreEntities = $this->getFactory()
            ->createCompanyStoreQuery()
            ->filterByFkCompany($idCompany)
            ->find();

        $relatedStores = new ArrayObject();

        foreach ($companyStoreEntities as $companyStoreEntity) {
            $storeTransfer = new StoreTransfer();
            $storeTransfer->setIdStore($companyStoreEntity->getFkStore());
            $relatedStores->append($storeTransfer);
        }

        return $relatedStores;
    }

    /**
     * {@inheritdoc}
     *
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function getCompanyById(int $idCompany): CompanyTransfer
    {
        $spyCompany = $this->getFactory()
            ->createCompanyQuery()
            ->filterByIdCompany($idCompany)
            ->findOne();

        return $this->getFactory()
            ->createCompanyMapper()
            ->mapEntityToCompanyTransfer($spyCompany, new CompanyTransfer());
    }

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer|null
     */
    public function findCompanyById(int $idCompany): ?CompanyTransfer
    {
        $companyEntity = $this->getFactory()
            ->createCompanyQuery()
            ->filterByIdCompany($idCompany)
            ->findOne();

        if (!$companyEntity) {
            return null;
        }

        return $this->getFactory()
            ->createCompanyMapper()
            ->mapEntityToCompanyTransfer($companyEntity, new CompanyTransfer());
    }

    /**
     * {@inheritdoc}
     *
     * @return \Generated\Shared\Transfer\CompanyCollectionTransfer
     */
    public function getCompanies(): CompanyCollectionTransfer
    {
        $spyCompanies = $this->buildQueryFromCriteria(
            $this->getFactory()->createCompanyQuery()
        )->find();

        $spyCompanies = new ArrayObject($spyCompanies);
        $companyTypeCollection = new CompanyCollectionTransfer();
        $companyTypeCollection->setCompanies($spyCompanies);

        return $companyTypeCollection;
    }

    /**
     * @param string $companyUuid
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer|null
     */
    public function findCompanyByUuid(string $companyUuid): ?CompanyTransfer
    {
        $companyEntity = $this->getFactory()
            ->createCompanyQuery()
            ->filterByUuid($companyUuid)
            ->findOne();

        if (!$companyEntity) {
            return null;
        }

        return $this->getFactory()
            ->createCompanyMapper()
            ->mapEntityToCompanyTransfer($companyEntity, new CompanyTransfer());
    }
}
