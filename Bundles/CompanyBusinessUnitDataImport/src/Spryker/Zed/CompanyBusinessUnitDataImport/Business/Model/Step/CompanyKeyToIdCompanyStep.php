<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\Step;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\DataSet\CompanyBusinessUnitDataSet;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyKeyToIdCompanyStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idCompanyListCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[CompanyBusinessUnitDataSet::ID_COMPANY] = $this->getIdCompanyByKey($dataSet[CompanyBusinessUnitDataSet::COMPANY_KEY]);
    }

    /**
     * @param string $companyKey
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCompanyByKey(string $companyKey): int
    {
        if (isset($this->idCompanyListCache[$companyKey])) {
            return $this->idCompanyListCache[$companyKey];
        }

        $idCompany = $this->createCompanyQuery()
            ->filterByKey($companyKey)
            ->select(SpyCompanyTableMap::COL_ID_COMPANY)
            ->findOne();

        if (!$idCompany) {
            throw new EntityNotFoundException(sprintf('Could not find company by key "%s"', $companyKey));
        }

        $this->idCompanyListCache[$companyKey] = $idCompany;

        return $this->idCompanyListCache[$companyKey];
    }

    /**
     * @return \Orm\Zed\Company\Persistence\SpyCompanyQuery
     */
    protected function createCompanyQuery(): SpyCompanyQuery
    {
        return SpyCompanyQuery::create();
    }
}
