<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyUserDataImport;

use Codeception\Actor;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class CompanyUserDataImportCommunicationTester extends Actor
{
    use _generated\CompanyUserDataImportCommunicationTesterActions;

    protected const ERROR_MESSAGE_FOUND = 'Found at least one entry in the database table but database table `%s` was expected to be empty.';
    protected const ERROR_MESSAGE_EXPECTED = 'Expected at least one entry in the database table `%s` but table is empty.';

    /**
     * @param array $keys
     *
     * @return void
     */
    public function truncateCompanyUsers(array $keys = []): void
    {
        $this->getCompanyUserQuery()->filterByKey_In($keys)->delete();
    }

    /**
     * @param array $keys
     *
     * @return void
     */
    public function assertCompanyUserTableDoesNotContainsRecords(array $keys = []): void
    {
        $this->assertFalse($this->getCompanyUserQuery()->filterByKey_In($keys)->exists(), sprintf(static::ERROR_MESSAGE_FOUND, SpyCompanyUserTableMap::TABLE_NAME));
    }

    /**
     * @param array $keys
     *
     * @return void
     */
    public function assertCompanyUserTableContainRecords(array $keys = []): void
    {
        $this->assertTrue($this->getCompanyUserQuery()->filterByKey_In($keys)->exists(), sprintf(static::ERROR_MESSAGE_EXPECTED, SpyCompanyUserTableMap::TABLE_NAME));
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createCustomerPreservingCustomerReference(array $seed = []): CustomerTransfer
    {
        $customerTransfer = $this->haveCustomer($seed);
        $customerTransfer->setCustomerReference($seed[CustomerTransfer::CUSTOMER_REFERENCE]);

        return $this->getCustomerFacade()->updateCustomer($customerTransfer)->getCustomerTransfer();
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    public function getCustomerFacade(): CustomerFacadeInterface
    {
        return $this->getLocator()->customer()->facade();
    }

    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function getCompanyUserQuery(): SpyCompanyUserQuery
    {
        return SpyCompanyUserQuery::create();
    }
}
