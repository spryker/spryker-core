<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\QuoteRequestDataImport\Business\QuoteRequestDataImportStep;

use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\QuoteRequestDataImport\Business\DataSet\QuoteRequestDataSetInterface;

class CompanyUserKeyToIdCompanyUser implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idCompanyUserCache;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $companyUserKey = $dataSet[QuoteRequestDataSetInterface::COLUMN_COMPANY_USER_KEY];

        if (!isset($this->idCompanyUserCache[$companyUserKey])) {
            $idCompanyUser = $this->createCompanyUserQuery()
                ->select([SpyCompanyUserTableMap::COL_ID_COMPANY_USER])
                ->findOneByKey($companyUserKey);

            if (!$idCompanyUser) {
                throw new EntityNotFoundException(sprintf('Could not find company user by key "%s"', $companyUserKey));
            }

            $this->idCompanyUserCache[$companyUserKey] = $idCompanyUser;
        }

        $dataSet[QuoteRequestDataSetInterface::ID_COMPANY_USER] = $this->idCompanyUserCache[$companyUserKey];
    }

    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function createCompanyUserQuery(): SpyCompanyUserQuery
    {
        return SpyCompanyUserQuery::create();
    }
}
