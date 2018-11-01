<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Model;

use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelQuery;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Model\DataSet\CompanyUnitAddressLabelDataSet;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyUnitAddressLabelWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $companyUnitAddressLabelEntity = SpyCompanyUnitAddressLabelQuery::create()
            ->filterByName($dataSet[CompanyUnitAddressLabelDataSet::LABEL_NAME])
            ->findOneOrCreate();

        $companyUnitAddressLabelEntity->save();
    }
}
