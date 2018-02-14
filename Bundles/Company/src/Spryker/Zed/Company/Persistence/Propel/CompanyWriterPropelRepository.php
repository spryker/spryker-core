<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Persistence\Propel;

use Generated\Shared\Transfer\CompanyTransfer;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\Company\Persistence\SpyCompanyStore;
use Orm\Zed\Company\Persistence\SpyCompanyStoreQuery;
use Spryker\Zed\Company\Persistence\CompanyWriterRepositoryInterface;

class CompanyWriterPropelRepository extends AbstractPropelRepository implements CompanyWriterRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function save(CompanyTransfer $companyTransfer): CompanyTransfer
    {
        $companyEntity = $this->getFactory()->createCompanyMapper()->mapCompanyTransferToEntity($companyTransfer);
        $companyEntity->save();

        return $this->getFactory()->createCompanyMapper()->mapCompanyEntityToTransfer($companyEntity);
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return void
     */
    public function delete(CompanyTransfer $companyTransfer): void
    {
        $companyTransfer->requireIdCompany();
        $this->queryCompany()->filterByIdCompany($companyTransfer->getIdCompany())->delete();
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
            (new SpyCompanyStore())
                ->setFkStore($idStore)
                ->setFkCompany($idCompany)
                ->save();
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

        $this->queryCompanyStore()->filterByFkCompany($idCompany)
            ->filterByFkStore_In($idStores)
            ->delete();
    }

    /**
     * @return \Orm\Zed\Company\Persistence\SpyCompanyQuery
     */
    protected function queryCompany(): SpyCompanyQuery
    {
        return $this->getFactory()->createCompanyQuery();
    }

    /**
     * @return \Orm\Zed\Company\Persistence\SpyCompanyStoreQuery
     */
    protected function queryCompanyStore(): SpyCompanyStoreQuery
    {
        return $this->getFactory()->createCompanyStoreQuery();
    }
}
