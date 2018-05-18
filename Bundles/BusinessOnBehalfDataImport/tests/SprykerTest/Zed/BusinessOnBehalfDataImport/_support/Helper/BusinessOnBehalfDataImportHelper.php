<?php

namespace SprykerTest\Zed\BusinessOnBehalfDataImport\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyBuilder;
use Generated\Shared\DataBuilder\CompanyBusinessUnitBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class BusinessOnBehalfDataImportHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    protected const CUSTOMER_REFERENCE = 'TEST--1';
    protected const COMPANY_KEY = 'test-company';
    protected const BUSINESS_UNIT_KEY = 'test-business-unit';

    /**
     * @return void
     */
    public function prepareTestData(): void
    {
        $this->createTestCustomer();
        $companyTransfer = $this->createTestCompany();
        $this->createTestBusinessUnit($companyTransfer);
    }

    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $companyUserQuery = $this->getCompanyUserQuery();
        $companyUserQuery->deleteAll();
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $companyUserQuery = $this->getCompanyUserQuery();
        $this->assertTrue(($companyUserQuery->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function getCompanyUserQuery(): SpyCompanyUserQuery
    {
        return SpyCompanyUserQuery::create();
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createTestCustomer(): CustomerTransfer
    {
        $customerTransfer = (new CustomerBuilder([
            'customer_reference' => static::CUSTOMER_REFERENCE,
        ]))->build();
        $customerEntity = SpyCustomerQuery::create()
            ->filterByCustomerReference(static::CUSTOMER_REFERENCE)
            ->findOneOrCreate();

        $customerEntity->fromArray($customerTransfer->toArray());
        $customerEntity->save();
        $customerTransfer->fromArray($customerEntity->toArray(), true);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($customerTransfer) {
            $this->cleanupCustomer($customerTransfer);
        });

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function cleanupCustomer(CustomerTransfer $customerTransfer): void
    {
        $this->getLocator()->customer()->facade()->deleteCustomer($customerTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    protected function createTestCompany(): CompanyTransfer
    {
        $companyTransfer = (new CompanyBuilder(['key' => static::COMPANY_KEY]))->build();
        $companyTransfer = $this->getLocator()->company()->facade()->create($companyTransfer)->getCompanyTransfer();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($companyTransfer) {
            $this->cleanupCompany($companyTransfer);
        });

        return $companyTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return void
     */
    protected function cleanupCompany(CompanyTransfer $companyTransfer): void
    {
        $this->getLocator()->company()->facade()->delete($companyTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    protected function createTestBusinessUnit(CompanyTransfer $companyTransfer): CompanyBusinessUnitTransfer
    {
        $businessUnitTransfer = (new CompanyBusinessUnitBuilder(['key' => static::BUSINESS_UNIT_KEY]))->build();
        $businessUnitTransfer->setIdCompanyBusinessUnit(null);
        $businessUnitTransfer->setFkCompany($companyTransfer->getIdCompany());
        $businessUnitTransfer = $this->getLocator()->companyBusinessUnit()->facade()->create($businessUnitTransfer)->getCompanyBusinessUnitTransfer();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($businessUnitTransfer) {
            $this->cleanupBusinessUnit($businessUnitTransfer);
        });

        return $businessUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $businessUnitTransfer
     *
     * @return void
     */
    protected function cleanupBusinessUnit(CompanyBusinessUnitTransfer $businessUnitTransfer): void
    {
        $this->getLocator()->companyBusinessUnit()->facade()->delete($businessUnitTransfer);
    }
}
