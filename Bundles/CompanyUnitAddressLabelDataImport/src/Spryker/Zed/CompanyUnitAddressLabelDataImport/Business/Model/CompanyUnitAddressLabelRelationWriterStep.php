<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Model;

use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Model\DataSet\CompanyUnitAddressLabelRelationDataSet;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyUnitAddressLabelRelationWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $companyUnitAddressLabelToCompanyUnitAddressEntity = SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery::create()
            ->filterByFkCompanyUnitAddress($dataSet[CompanyUnitAddressLabelRelationDataSet::ID_COMPANY_UNIT_ADDRESS])
            ->filterByFkCompanyUnitAddressLabel($dataSet[CompanyUnitAddressLabelRelationDataSet::ID_COMPANY_UNIT_ADDRESS_LABEL])
            ->findOneOrCreate();

        $companyUnitAddressLabelToCompanyUnitAddressEntity->save();
    }
}
