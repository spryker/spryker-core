<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Model\Step;

use Orm\Zed\CompanyUnitAddressLabel\Persistence\Map\SpyCompanyUnitAddressLabelTableMap;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelQuery;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Exception\CompanyUnitAddressLabelNotFoundException;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Model\DataSet\CompanyUnitAddressLabelRelationDataSet;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyUnitAddressLabelNameToIdCompanyUnitAddressLabelStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idCompanyUnitAddressLabelCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Exception\CompanyUnitAddressLabelNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $labelName = $dataSet[CompanyUnitAddressLabelRelationDataSet::LABEL_NAME];
        if (!isset($this->idCompanyUnitAddressLabelCache[$labelName])) {
            $companyUnitAddressLabelQuery = new SpyCompanyUnitAddressLabelQuery();
            $idCompanyUnitAddressLabel = $companyUnitAddressLabelQuery
                ->select(SpyCompanyUnitAddressLabelTableMap::COL_ID_COMPANY_UNIT_ADDRESS_LABEL)
                ->findOneByName($labelName);

            if (!$idCompanyUnitAddressLabel) {
                throw new CompanyUnitAddressLabelNotFoundException(sprintf('Could not find CompanyUnitAddressLabel with name "%s".', $labelName));
            }

            $this->idCompanyUnitAddressLabelCache[$labelName] = $idCompanyUnitAddressLabel;
        }

        $dataSet[CompanyUnitAddressLabelRelationDataSet::ID_COMPANY_UNIT_ADDRESS_LABEL] = $this->idCompanyUnitAddressLabelCache[$labelName];
    }
}
