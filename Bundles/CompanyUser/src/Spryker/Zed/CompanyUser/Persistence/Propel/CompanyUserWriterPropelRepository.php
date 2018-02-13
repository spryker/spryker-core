<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Persistence\Propel;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserPersistenceFactory;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserWriterRepositoryInterface;

class CompanyUserWriterPropelRepository implements CompanyUserWriterRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function save(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        $companyUserEntity = $this->getFactory()->createCompanyUserMapper()->mapCompanyUserTransferToEntity($companyUserTransfer);
        $companyUserEntity->save();

        return $this->getFactory()->createCompanyUserMapper()->mapCompanyUserEntityToTransfer($companyUserEntity);
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Persistence\CompanyUserPersistenceFactory
     */
    protected function getFactory(): CompanyUserPersistenceFactory
    {
        return new CompanyUserPersistenceFactory();
    }
}
