<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\BusinessOnBehalfDataImport\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Map\RelationMap;
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
        $this->cleanTableRelations($this->getCompanyUserQuery());
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param array $processedEntities
     *
     * @return void
     */
    protected function cleanTableRelations(ModelCriteria $query, array $processedEntities = []): void
    {
        $relations = $query->getTableMap()->getRelations();

        foreach ($relations as $relationMap) {
            $relationType = $relationMap->getType();
            $fullyQualifiedQueryModel = $relationMap->getLocalTable()->getClassname() . 'Query';
            if ($relationType === RelationMap::ONE_TO_MANY && !in_array($fullyQualifiedQueryModel, $processedEntities)) {
                $processedEntities[] = $fullyQualifiedQueryModel;
                $fullyQualifiedQueryModelObject = $fullyQualifiedQueryModel::create();
                $this->cleanTableRelations($fullyQualifiedQueryModelObject, $processedEntities);
            }
        }

        $query->deleteAll();
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
