<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Model;

use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Model\DataSet\CompanyUnitAddressLabelRelation;
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
            ->filterByFkCompanyUnitAddress($dataSet[CompanyUnitAddressLabelRelation::DATA_SET_ID_COMPANY_UNIT_ADDRESS])
            ->filterByFkCompanyUnitAddressLabel($dataSet[CompanyUnitAddressLabelRelation::DATA_SET_ID_COMPANY_UNIT_ADDRESS_LABEL])
            ->findOneOrCreate();

        $companyUnitAddressLabelToCompanyUnitAddressEntity->save();
    }
}
