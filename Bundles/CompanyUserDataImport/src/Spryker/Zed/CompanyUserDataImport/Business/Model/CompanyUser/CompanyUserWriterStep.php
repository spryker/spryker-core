<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyUserDataImport\Business\Model\CompanyUser;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Spryker\Zed\CompanyUserDataImport\Business\Model\DataSet\CompanyUserDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyUserWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @module CompanyUser
     *
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $idCustomer = $this->getIdCustomerByReference($dataSet[CompanyUserDataSetInterface::COLUMN_CUSTOMER_REFERENCE]);
        $idCompany = $this->getIdCompanyByKey($dataSet[CompanyUserDataSetInterface::COLUMN_COMPANY_KEY]);
        $idCompanyBusinessUnit = $this->getIdCompanyBusinessUnitByKey($dataSet[CompanyUserDataSetInterface::COLUMN_BUSINESS_UNIT_KEY]);

        $companyUserEntity = SpyCompanyUserQuery::create()
            ->filterByKey($dataSet[CompanyUserDataSetInterface::COLUMN_COMPANY_USER_KEY])
            ->findOneOrCreate();

        $companyUserEntity
            ->setFkCustomer($idCustomer)
            ->setFkCompany($idCompany)
            ->setFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->setIsDefault((bool)$dataSet[CompanyUserDataSetInterface::COLUMN_COMPANY_USER_KEY])
            ->save();
    }

    /**
     * @param string $customerReference
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCustomerByReference(string $customerReference): int
    {
        $idCustomer = SpyCustomerQuery::create()
            ->select(SpyCustomerTableMap::COL_ID_CUSTOMER)
            ->findOneByCustomerReference($customerReference);

        if (!$idCustomer) {
            throw new EntityNotFoundException(sprintf('Could not find customer by reference "%s"', $customerReference));
        }

        return $idCustomer;
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
        $idCompany = $this->getCompanyQuery()
            ->select(SpyCompanyTableMap::COL_ID_COMPANY)
            ->findOneByKey($companyKey);

        if (!$idCompany) {
            throw new EntityNotFoundException(sprintf('Could not find company by key "%s"', $companyKey));
        }

        return $idCompany;
    }

    /**
     * @param string $companyBusinessUnitKey
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCompanyBusinessUnitByKey(string $companyBusinessUnitKey): int
    {
        $idCompanyBusinessUnit = $this->getCompanyBusinessUnitQuery()
            ->select(SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT)
            ->findOneByKey($companyBusinessUnitKey);

        if (!$idCompanyBusinessUnit) {
            throw new EntityNotFoundException(sprintf('Could not find company business unit by key "%s"', $idCompanyBusinessUnit));
        }

        return $idCompanyBusinessUnit;
    }

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    protected function getCustomerQuery(): SpyCustomerQuery
    {
        return SpyCustomerQuery::create();
    }

    /**
     * @return \Orm\Zed\Company\Persistence\SpyCompanyQuery
     */
    protected function getCompanyQuery(): SpyCompanyQuery
    {
        return SpyCompanyQuery::create();
    }

    /**
     * @return \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery
     */
    protected function getCompanyBusinessUnitQuery(): SpyCompanyBusinessUnitQuery
    {
        return SpyCompanyBusinessUnitQuery::create();
    }
}
