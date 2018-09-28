<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyBusinessUnitDataImport\Persistence;

use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitDataImport\Persistence\CompanyBusinessUnitDataImportPersistenceFactory getFactory()
 */
class CompanyBusinessUnitDataImportRepository extends AbstractRepository implements CompanyBusinessUnitDataImportRepositoryInterface
{
    /**
     * @var int[]
     */
    protected $idCompanyBusinessUnitListCache = [];

    /**
     * @param string $companyBusinessUnitKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    public function getIdCompanyBusinessUnitByKey(string $companyBusinessUnitKey): int
    {
        if (isset($this->idCompanyBusinessUnitListCache[$companyBusinessUnitKey])) {
            return $this->idCompanyBusinessUnitListCache[$companyBusinessUnitKey];
        }

        $idCompanyBusinessUnit = $this->getFactory()->createCompanyBusinessUnitQuery()
            ->filterByKey($companyBusinessUnitKey)
            ->select(SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT)
            ->findOne();

        if (!$idCompanyBusinessUnit) {
            throw new EntityNotFoundException(sprintf('Could not find company business unit by key "%s"', $companyBusinessUnitKey));
        }

        $this->idCompanyBusinessUnitListCache[$companyBusinessUnitKey] = $idCompanyBusinessUnit;

        return $this->idCompanyBusinessUnitListCache[$companyBusinessUnitKey];
    }
}
