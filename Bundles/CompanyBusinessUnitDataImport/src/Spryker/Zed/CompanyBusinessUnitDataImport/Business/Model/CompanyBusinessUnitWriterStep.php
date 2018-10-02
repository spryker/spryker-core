<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
    public function execute(DataSetInterface $dataSet): void
    {
        $companyBusinessUnitEntity = SpyCompanyBusinessUnitQuery::create()
            ->filterByKey($dataSet[CompanyBusinessUnitDataSet::BUSINESS_UNIT_KEY])
            ->filterByFkCompany($dataSet[CompanyBusinessUnitDataSet::ID_COMPANY])
            ->findOneOrCreate();

        $companyBusinessUnitEntity->fromArray($dataSet->getArrayCopy());

        $companyBusinessUnitEntity->save();
    }
}
