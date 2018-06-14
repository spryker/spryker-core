<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\Step\CompanyUser;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\DataSet\BusinessOnBehalfCompanyUserDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyKeyToIdCompanyStep implements DataImportStepInterface
{
    /**
     * @var int[] Keys are company keys.
     */
    protected $idCompanyBuffer = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $companyKey = $dataSet[BusinessOnBehalfCompanyUserDataSetInterface::COLUMN_COMPANY_KEY];

        $dataSet[BusinessOnBehalfCompanyUserDataSetInterface::COLUMN_ID_COMPANY] = $this->getIdCompany($companyKey);
    }

    /**
     * @uses SpyCompanyQuery
     *
     * @param string $companyKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCompany(string $companyKey): int
    {
        if (isset($this->idCompanyBuffer[$companyKey])) {
            return $this->idCompanyBuffer[$companyKey];
        }

        $idCompany = SpyCompanyQuery::create()
            ->select(SpyCompanyTableMap::COL_ID_COMPANY)
            ->findOneByKey($companyKey);

        if (!$idCompany) {
            throw new EntityNotFoundException(sprintf('Could not find company by key "%s"', $companyKey));
        }

        $this->idCompanyBuffer[$companyKey] = $idCompany;

        return $this->idCompanyBuffer[$companyKey];
    }
}
