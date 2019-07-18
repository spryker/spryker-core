<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyRoleDataImport\Business\Model\CompanyRole;

use Orm\Zed\CompanyRole\Persistence\Map\SpyCompanyRoleTableMap;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;

abstract class AbstractCompanyRoleWriterStep
{
    /**
     * @var int[]
     */
    protected $idCompanyRoleListCache = [];

    /**
     * @param string $companyRoleKey
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCompanyRoleByKey(string $companyRoleKey): int
    {
        if (!isset($this->idCompanyRoleListCache[$companyRoleKey])) {
            $idCompanyRole = $this->getCompanyRoleQuery()
                ->filterByKey($companyRoleKey)
                ->select(SpyCompanyRoleTableMap::COL_ID_COMPANY_ROLE)
                ->findOne();

            if (!$idCompanyRole) {
                throw new EntityNotFoundException(sprintf('Could not find company role by key "%s"', $companyRoleKey));
            }

            $this->idCompanyRoleListCache[$companyRoleKey] = $idCompanyRole;
        }

        return $this->idCompanyRoleListCache[$companyRoleKey];
    }

    /**
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery
     */
    protected function getCompanyRoleQuery(): SpyCompanyRoleQuery
    {
        return SpyCompanyRoleQuery::create();
    }
}
