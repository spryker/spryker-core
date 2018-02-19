<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Persistence\Propel;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitPersistenceFactory;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitWriterRepositoryInterface;

class CompanyBusinessUnitWriterPropelRepository implements CompanyBusinessUnitWriterRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function save(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitTransfer
    {
        $companyBusinessUnitEntity = $this->getFactory()->createCompanyBusinessUnitMapper()->mapCompanyBusinessUnitTransferToEntity($companyBusinessUnitTransfer);
        $companyBusinessUnitEntity->save();

        return $this->getFactory()->createCompanyBusinessUnitMapper()->mapCompanyBusinessUnitEntityToTransfer($companyBusinessUnitEntity);
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return void
     */
    public function delete(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): void
    {
        $companyBusinessUnitTransfer->requireIdCompanyBusinessUnit();

        $this->queryCompanyBusinessUnit()->filterByIdCompanyBusinessUnit(
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit()
        )->delete();
    }

    /**
     * @return \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery
     */
    protected function queryCompanyBusinessUnit(): SpyCompanyBusinessUnitQuery
    {
        return $this->getFactory()->createCompanyBusinessUnitQuery();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitPersistenceFactory
     */
    protected function getFactory(): CompanyBusinessUnitPersistenceFactory
    {
        return new CompanyBusinessUnitPersistenceFactory();
    }
}
