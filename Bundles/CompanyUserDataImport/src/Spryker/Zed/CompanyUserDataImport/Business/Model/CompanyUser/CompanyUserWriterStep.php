<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyUserDataImport\Business\Model\CompanyUser;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Spryker\Zed\CompanyUserDataImport\Business\Model\DataSet\CompanyUserDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyUserWriterStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idCustomerListCache = [];

    /**
     * @var int[]
     */
    protected $idCompanyListCache = [];

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

        $companyUserEntity = SpyCompanyUserQuery::create()
            ->filterByKey($dataSet[CompanyUserDataSetInterface::COLUMN_COMPANY_USER_KEY])
            ->findOneOrCreate();

        $companyUserEntity
            ->setFkCustomer($idCustomer)
            ->setFkCompany($idCompany)
            ->setIsDefault((bool)$dataSet[CompanyUserDataSetInterface::COLUMN_IS_DEFAULT])
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
        if (!isset($this->idCustomerListCache[$customerReference])) {
            $idCustomer = $this->getCustomerQuery()
                ->filterByCustomerReference($customerReference)
                ->select(SpyCustomerTableMap::COL_ID_CUSTOMER)
                ->findOne();

            if (!$idCustomer) {
                throw new EntityNotFoundException(sprintf('Could not find customer by reference "%s"', $customerReference));
            }

            $this->idCustomerListCache[$customerReference] = $idCustomer;
        }

        return $this->idCustomerListCache[$customerReference];
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
        if (!isset($this->idCompanyListCache[$companyKey])) {
            $idCompany = $this->getCompanyQuery()
                ->filterByKey($companyKey)
                ->select(SpyCompanyTableMap::COL_ID_COMPANY)
                ->findOne();

            if (!$idCompany) {
                throw new EntityNotFoundException(sprintf('Could not find company by key "%s"', $companyKey));
            }

            $this->idCompanyListCache[$companyKey] = $idCompany;
        }

        return $this->idCompanyListCache[$companyKey];
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
}
