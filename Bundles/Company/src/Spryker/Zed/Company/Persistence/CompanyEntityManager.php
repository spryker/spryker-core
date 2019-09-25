<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Persistence;

use Generated\Shared\Transfer\CompanyTransfer;
use Orm\Zed\Company\Persistence\SpyCompanyStore;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Company\Persistence\CompanyPersistenceFactory getFactory()
 */
class CompanyEntityManager extends AbstractEntityManager implements CompanyEntityManagerInterface
{
    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function saveCompany(CompanyTransfer $companyTransfer): CompanyTransfer
    {
        $spyCompany = $this->getFactory()
            ->createCompanyQuery()
            ->filterByIdCompany($companyTransfer->getIdCompany())
            ->findOneOrCreate();

        $spyCompany = $this->getFactory()
            ->createCompanyMapper()
            ->mapCompanyTransferToEntity($companyTransfer, $spyCompany);

        $spyCompany->save();

        $companyTransfer->setIdCompany($spyCompany->getIdCompany());

        return $companyTransfer;
    }

    /**
     * {@inheritDoc}
     *
     * @param int $idCompany
     *
     * @return void
     */
    public function deleteCompanyById(int $idCompany): void
    {
        $this->getFactory()
            ->createCompanyQuery()
            ->filterByIdCompany($idCompany)
            ->delete();
    }

    /**
     * {@inheritDoc}
     *
     * @param array $idStores
     * @param int $idCompany
     *
     * @return void
     */
    public function addStores(array $idStores, $idCompany): void
    {
        foreach ($idStores as $idStore) {
            $companyStoreEntityTransfer = new SpyCompanyStore();
            $companyStoreEntityTransfer->setFkCompany($idCompany)
                ->setFkStore($idStore)
                ->save();
        }
    }

    /**
     * {@inheritDoc}
     *
     * @param array $idStores
     * @param int $idCompany
     *
     * @return void
     */
    public function removeStores(array $idStores, $idCompany): void
    {
        if (count($idStores) === 0) {
            return;
        }

        $this->getFactory()
            ->createCompanyStoreQuery()
            ->filterByFkCompany($idCompany)
            ->filterByFkStore_In($idStores)
            ->delete();
    }
}
