<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportStep;

use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShoppingListDataImport\Business\DataSet\ShoppingListCompanyBusinessUnitDataSetInterface;

class BusinessUnitKeyToIdCompanyBusinessUnitStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idCompanyBusinessUnitCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $companyBusinessUnitKey = $dataSet[ShoppingListCompanyBusinessUnitDataSetInterface::COLUMN_COMPANY_BUSINESS_UNIT_KEY];
        if (!isset($this->idCompanyBusinessUnitCache[$companyBusinessUnitKey])) {
            $companyBusinessUnitQuery = new SpyCompanyBusinessUnitQuery();
            $idCompanyUser = $companyBusinessUnitQuery
                ->select([SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT])
                ->findOneByKey($companyBusinessUnitKey);

            if (!$idCompanyUser) {
                throw new EntityNotFoundException(sprintf('Could not find company business unit by key "%s"', $companyBusinessUnitKey));
            }

            $this->idCompanyBusinessUnitCache[$companyBusinessUnitKey] = $idCompanyUser;
        }

        $dataSet[ShoppingListCompanyBusinessUnitDataSetInterface::ID_COMPANY_BUSINESS_UNIT] = $this->idCompanyBusinessUnitCache[$companyBusinessUnitKey];
    }
}
