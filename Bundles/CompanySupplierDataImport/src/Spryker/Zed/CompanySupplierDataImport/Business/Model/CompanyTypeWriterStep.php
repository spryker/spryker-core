<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierDataImport\Business\Model;

use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\CompanySupplier\Persistence\SpyCompanyTypeQuery;
use Spryker\Zed\CompanySupplierDataImport\Business\Model\DataSet\CompanySupplierDataSet;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyTypeWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $companyTypeEntity = SpyCompanyTypeQuery::create()
            ->filterByName($dataSet[CompanySupplierDataSet::COMPANY_TYPE])
            ->findOneOrCreate();
        $companyTypeEntity->save();

        $companyEntity = SpyCompanyQuery::create()
            ->findOneByIdCompany($dataSet[CompanySupplierDataSet::COMPANY_ID]);
        $companyEntity->setFkCompanyType($companyTypeEntity->getIdCompanyType());
        $companyEntity->save();
    }
}
