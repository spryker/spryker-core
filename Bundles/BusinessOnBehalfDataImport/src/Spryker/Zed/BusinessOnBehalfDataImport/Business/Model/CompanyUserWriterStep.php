<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\BusinessOnBehalfDataImport\Business\Model;

use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\DataSet\BusinessOnBehalfDataSet;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyUserWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $companyUserEntity = SpyCompanyUserQuery::create()
            ->filterByFkCompany($dataSet[BusinessOnBehalfDataSet::ID_COMPANY])
            ->filterByFkCompanyBusinessUnit($dataSet[BusinessOnBehalfDataSet::ID_BUSINESS_UNIT])
            ->filterByFkCustomer($dataSet[BusinessOnBehalfDataSet::ID_CUSTOMER])
            ->findOneOrCreate();

        $companyUserEntity->save();
    }
}
