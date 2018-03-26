<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressDataImport\Business\Model;

use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;
use Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\DataSet\CompanyUnitAddressDataSet;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyUnitAddressWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $companyUnitAddressEntity = SpyCompanyUnitAddressQuery::create()
            ->filterByKey($dataSet[CompanyUnitAddressDataSet::ADDRESS_KEY])
            ->findOneOrCreate();

        $companyUnitAddressEntity->fromArray($dataSet->getArrayCopy());
        $companyUnitAddressEntity
            ->setFkCompany($dataSet[CompanyUnitAddressDataSet::ID_COMPANY])
            ->setFkCountry($dataSet[CompanyUnitAddressDataSet::ID_COUNTRY]);

        $companyUnitAddressEntity->save();
    }
}
