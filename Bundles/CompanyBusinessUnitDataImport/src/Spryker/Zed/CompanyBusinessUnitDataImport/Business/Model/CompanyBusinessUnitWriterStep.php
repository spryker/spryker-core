<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model;

use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\DataSet\CompanyBusinessUnitDataSet;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyBusinessUnitWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $companyBusinessUnitEntity = SpyCompanyBusinessUnitQuery::create()
            ->filterByKey($dataSet[CompanyBusinessUnitDataSet::BUSINESS_UNIT_KEY])
            ->filterByFkCompany($dataSet[CompanyBusinessUnitDataSet::ID_COMPANY])
            ->findOneOrCreate();

        $companyBusinessUnitEntity->fromArray($dataSet->getArrayCopy());

        $companyBusinessUnitEntity->save();
    }
}
