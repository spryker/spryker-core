<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\BusinessOnBehalfDataImport\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CustomerBuilder;
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
}
