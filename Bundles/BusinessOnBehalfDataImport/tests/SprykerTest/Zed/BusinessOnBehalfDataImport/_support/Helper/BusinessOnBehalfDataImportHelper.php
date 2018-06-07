<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerTest\Zed\BusinessOnBehalfDataImport\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class BusinessOnBehalfDataImportHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    protected const CUSTOMER_REFERENCE = 'TEST--1';

    /**
     * @return void
     */
    public function prepareTestData(): void
    {
        $this->createTestCustomer();
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
     * @return void
     */
    public function assertDatabaseTableContainsCorrectData(): void
    {
        $companyUserQuery = $this->getCompanyUserQuery();
        $companyUserQuery->filterByFkCustomer(
            $this->getCustomerQuery()->findOneByCustomerReference(static::CUSTOMER_REFERENCE)->getIdCustomer()
        );
        $this->assertTrue(($companyUserQuery->count() > 0), 'Expected entry in the database table with correct data but no one found.');
    }

    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function getCompanyUserQuery(): SpyCompanyUserQuery
    {
        return SpyCompanyUserQuery::create();
    }

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    protected function getCustomerQuery(): SpyCustomerQuery
    {
        return SpyCustomerQuery::create();
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createTestCustomer(): CustomerTransfer
    {
        $customerTransfer = (new CustomerBuilder([
            'customer_reference' => static::CUSTOMER_REFERENCE,
        ]))->build();
        $customerEntity = $this->getCustomerQuery()
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
        $this->getCustomerFacade()->deleteCustomer($customerTransfer);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    protected function getCustomerFacade(): CustomerFacadeInterface
    {
        return $this->getLocator()->customer()->facade();
    }
}
