<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Persistence;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\SpyCompanyStoreEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Company\Persistence\CompanyPersistenceFactory getFactory()
 */
class CompanyEntityManager extends AbstractEntityManager implements CompanyEntityManagerInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function saveCompany(CompanyTransfer $companyTransfer): CompanyTransfer
    {
        $entityTransfer = $this->getFactory()
            ->createCompanyMapper()
            ->mapCompanyTransferToEntityTransfer($companyTransfer);
        $entityTransfer = $this->save($entityTransfer);

        return $this->getFactory()
            ->createCompanyMapper()
            ->mapEntityTransferToCompanyTransfer($entityTransfer);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @param array $idStores
     * @param int $idCompany
     *
     * @return void
     */
    public function addStores(array $idStores, $idCompany): void
    {
        foreach ($idStores as $idStore) {
            $companyStoreEntityTransfer = new SpyCompanyStoreEntityTransfer();
            $companyStoreEntityTransfer->setFkCompany($idCompany)
                ->setFkStore($idStore);
            $this->save($companyStoreEntityTransfer);
        }
    }

    /**
     * {@inheritdoc}
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
