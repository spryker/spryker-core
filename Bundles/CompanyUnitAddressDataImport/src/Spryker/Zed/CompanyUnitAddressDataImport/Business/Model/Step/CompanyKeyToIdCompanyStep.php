<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Step;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\DataSet\CompanyUnitAddressDataSet;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyKeyToIdCompanyStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idCompanyCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $companyKey = $dataSet[CompanyUnitAddressDataSet::COMPANY_KEY];
        if (!isset($this->idCompanyCache[$companyKey])) {
            $companyQuery = new SpyCompanyQuery();
            $idCompany = $companyQuery
                ->select(SpyCompanyTableMap::COL_ID_COMPANY)
                ->findOneByKey($companyKey);

            if (!$idCompany) {
                throw new EntityNotFoundException(sprintf('Could not find company by key "%s"', $companyKey));
            }

            $this->idCompanyCache[$companyKey] = $idCompany;
        }

        $dataSet[CompanyUnitAddressDataSet::ID_COMPANY] = $this->idCompanyCache[$companyKey];
    }
}
