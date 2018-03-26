<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Model\Step;

use Orm\Zed\CompanyUnitAddress\Persistence\Map\SpyCompanyUnitAddressTableMap;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Exception\CompanyUnitAddressNotFoundException;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Model\DataSet\CompanyUnitAddressLabelRelationDataSet;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyUnitAddressKeyToIdCompanyUnitAddressStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idCompanyUnitAddressCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Exception\CompanyUnitAddressNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $addressKey = $dataSet[CompanyUnitAddressLabelRelationDataSet::ADDRESS_KEY];
        if (!isset($this->idCompanyUnitAddressCache[$addressKey])) {
            $companyUnitAddressQuery = new SpyCompanyUnitAddressQuery();
            $idCompanyUnitAddress = $companyUnitAddressQuery
                ->select(SpyCompanyUnitAddressTableMap::COL_ID_COMPANY_UNIT_ADDRESS)
                ->findOneByKey($addressKey);

            if (!$idCompanyUnitAddress) {
                throw new CompanyUnitAddressNotFoundException(sprintf('Could not find CompanyUnitAddress with key "%s".', $addressKey));
            }

            $this->idCompanyUnitAddressCache[$addressKey] = $idCompanyUnitAddress;
        }

        $dataSet[CompanyUnitAddressLabelRelationDataSet::ID_COMPANY_UNIT_ADDRESS] = $this->idCompanyUnitAddressCache[$addressKey];
    }
}
