<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Persistence;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStoragePersistenceFactory getFactory()
 */
class CompanyUserStorageEntityManager extends AbstractEntityManager implements CompanyUserStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer $companyUserStorageTransfer
     *
     * @return void
     */
    public function saveCompanyUserStorage(CompanyUserStorageTransfer $companyUserStorageTransfer): void
    {
        $companyUserStorageEntity = $this->getFactory()
            ->createCompanyUserStorageQuery()
            ->filterByFkCompanyUser($companyUserStorageTransfer->getIdCompanyUser())
            ->findOneOrCreate();

        $companyUserStorageEntity = $this->getFactory()
            ->createCompanyUserStorageMapper()
            ->mapCompanyUserStorageTransferToCompanyUserStorageEntity($companyUserStorageTransfer, $companyUserStorageEntity);

        $companyUserStorageEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer $companyUserStorageTransfer
     *
     * @return void
     */
    public function deleteCompanyUserStorage(CompanyUserStorageTransfer $companyUserStorageTransfer): void
    {
        $companyUserStorageEntity = $this->getFactory()
            ->createCompanyUserStorageQuery()
            ->filterByFkCompanyUser($companyUserStorageTransfer->getIdCompanyUser())
            ->findOne();

        if (!$companyUserStorageEntity) {
            return;
        }

        $companyUserStorageEntity->delete();
    }
}
