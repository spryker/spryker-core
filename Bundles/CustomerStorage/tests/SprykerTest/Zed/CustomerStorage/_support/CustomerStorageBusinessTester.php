<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerStorage;

use Codeception\Actor;
use DateTime;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\CustomerStorage\Persistence\SpyCustomerInvalidatedStorageQuery;
use Spryker\Zed\CustomerStorage\CustomerStorageConfig;

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
 * @method void pause()
 * @method \Spryker\Zed\CustomerStorage\Business\CustomerStorageFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CustomerStorageBusinessTester extends Actor
{
    use _generated\CustomerStorageBusinessTesterActions;

    /**
     * @uses \Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap::COL_PASSWORD
     *
     * @var string
     */
    protected const COL_PASSWORD = 'spy_customer.password';

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\InvalidatedCustomerTransfer|null
     */
    public function findCustomerInvalidatedStorage(CustomerTransfer $customerTransfer): ?InvalidatedCustomerTransfer
    {
        $customerInvalidatedStorageEntity = SpyCustomerInvalidatedStorageQuery::create()
            ->findOneByCustomerReference(
                $customerTransfer->getCustomerReference(),
            );
        if ($customerInvalidatedStorageEntity === null) {
            return null;
        }

        $data = $customerInvalidatedStorageEntity->getData();

        return (new InvalidatedCustomerTransfer())
            ->setAnonymizedAt(
                $data[CustomerStorageConfig::COL_ANONYMIZED_AT] ?? null,
            )
            ->setPasswordUpdatedAt(
                $data[CustomerStorageConfig::COL_PASSWORD_UPDATED_AT] ?? null,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \DateTime $createdAt
     *
     * @return void
     */
    public function createCustomerInvalidatedStorage(
        CustomerTransfer $customerTransfer,
        DateTime $createdAt
    ): void {
        $customerInvalidatedStorageEntity = SpyCustomerInvalidatedStorageQuery::create()
            ->filterByCustomerReference($customerTransfer->getCustomerReference())
            ->findOneOrCreate();

        $customerInvalidatedStorageEntity->setCreatedAt($createdAt->format('Y-m-d H:i:s'));
        $customerInvalidatedStorageEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\EventEntityTransfer
     */
    public function createEventEntityTransfer(CustomerTransfer $customerTransfer): EventEntityTransfer
    {
        return (new EventEntityTransfer())
            ->setId($customerTransfer->getIdCustomer())
            ->addModifiedColumns(static::COL_PASSWORD);
    }

    /**
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    public function createPaginationTransfer(): PaginationTransfer
    {
        return (new PaginationTransfer())
            ->setOffset(0)
            ->setLimit(10);
    }
}
