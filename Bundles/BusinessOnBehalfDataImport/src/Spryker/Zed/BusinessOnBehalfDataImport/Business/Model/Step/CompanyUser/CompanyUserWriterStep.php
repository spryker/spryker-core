<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\Step\CompanyUser;

use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\DataSet\BusinessOnBehalfCompanyUserDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyUserWriterStep implements DataImportStepInterface
{
    /**
     * @uses SpyCompanyUserQuery
     *
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $companyUserEntity = SpyCompanyUserQuery::create()
            ->filterByFkCompany($dataSet[BusinessOnBehalfCompanyUserDataSetInterface::COLUMN_ID_COMPANY])
            ->filterByFkCompanyBusinessUnit($dataSet[BusinessOnBehalfCompanyUserDataSetInterface::COLUMN_ID_BUSINESS_UNIT])
            ->filterByFkCustomer($dataSet[BusinessOnBehalfCompanyUserDataSetInterface::COLUMN_ID_CUSTOMER])
            ->findOneOrCreate();

        $companyUserEntity->save();
    }
}
