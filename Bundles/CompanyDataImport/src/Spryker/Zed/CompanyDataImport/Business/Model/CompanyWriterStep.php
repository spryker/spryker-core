<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyDataImport\Business\Model;

use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Spryker\Zed\CompanyDataImport\Business\Model\DataSet\CompanyDataSet;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $companyEntity = SpyCompanyQuery::create()
            ->filterByKey($dataSet[CompanyDataSet::COMPANY_KEY])
            ->findOneOrCreate();

        $companyEntity->fromArray($dataSet->getArrayCopy());

        $companyEntity->save();
    }
}
