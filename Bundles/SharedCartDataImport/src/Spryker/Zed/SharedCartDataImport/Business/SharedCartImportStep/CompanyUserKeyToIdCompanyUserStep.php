<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\SharedCartDataImport\Business\SharedCartImportStep;

use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\SharedCartDataImport\Business\DataSet\SharedCartDataSetInterface;

class CompanyUserKeyToIdCompanyUserStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idCompanyUserCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $companyUserKey = $dataSet[SharedCartDataSetInterface::KEY_COMPANY_USER];
        if (!isset($this->idCompanyUserCache[$companyUserKey])) {
            $companyUserQuery = new SpyCompanyUserQuery();
            $idCompanyUser = $companyUserQuery
                ->select(SpyCompanyUserTableMap::COL_ID_COMPANY_USER)
                ->findOneByKey($companyUserKey);

            if (!$idCompanyUser) {
                throw new EntityNotFoundException(sprintf('Could not find company user by key "%s"', $companyUserKey));
            }

            $this->idCompanyUserCache[$companyUserKey] = $idCompanyUser;
        }

        $dataSet[SharedCartDataSetInterface::ID_COMPANY_USER] = $this->idCompanyUserCache[$companyUserKey];
    }
}
