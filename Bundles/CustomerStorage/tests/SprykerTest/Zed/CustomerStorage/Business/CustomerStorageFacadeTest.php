<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerStorage\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use SprykerTest\Zed\CustomerStorage\CustomerStorageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CustomerStorage
 * @group Business
 * @group Facade
 * @group CustomerStorageFacadeTest
 * Add your own group annotations below this line
 */
class CustomerStorageFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CustomerStorage\CustomerStorageBusinessTester
     */
    protected CustomerStorageBusinessTester $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->tester->addDependencies();
    }

    /**
     * @return void
     */
    public function testWriteCustomerInvalidatedStorageCollectionByCustomerEvents(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::ANONYMIZED_AT => (new DateTime())->format('Y-m-d H:i:s'),
        ]);

        // Act
        $this->tester->getFacade()->writeCustomerInvalidatedStorageCollectionByCustomerEvents([
            $this->tester->createEventEntityTransfer($customerTransfer),
        ]);

        $customerInvalidatedStorage = $this->tester->findCustomerInvalidatedStorage($customerTransfer);

        // Assert
        $this->assertNotNull($customerInvalidatedStorage);
        $this->assertNotNull($customerInvalidatedStorage->getAnonymizedAt());
        $this->assertNotNull($customerInvalidatedStorage->getPasswordUpdatedAt());
    }

    /**
     * @return void
     */
    public function testDeleteExpiredCustomerInvalidatedStorage(): void
    {
        // Arrange
        $customerTransfer1 = $this->tester->haveCustomer();
        $customerTransfer2 = $this->tester->haveCustomer();
        $this->tester->createCustomerInvalidatedStorage($customerTransfer1, new DateTime());
        $this->tester->createCustomerInvalidatedStorage($customerTransfer2, new DateTime('-7 days'));

        // Act
        $this->tester->getFacade()->deleteExpiredCustomerInvalidatedStorage();
        $customerInvalidatedStorage1 = $this->tester->findCustomerInvalidatedStorage($customerTransfer1);
        $customerInvalidatedStorage2 = $this->tester->findCustomerInvalidatedStorage($customerTransfer2);

        // Assert
        $this->assertNotNull($customerInvalidatedStorage1);
        $this->assertNull($customerInvalidatedStorage2);
    }

    /**
     * @return void
     */
    public function testGetSynchronizationTransferCollection(): void
    {
        // Arrange
        $customerTransfer1 = $this->tester->haveCustomer();
        $customerTransfer2 = $this->tester->haveCustomer();
        $this->tester->createCustomerInvalidatedStorage($customerTransfer1, new DateTime());
        $this->tester->createCustomerInvalidatedStorage($customerTransfer2, new DateTime('-1 day'));
        $paginationTransfer = $this->tester->createPaginationTransfer();

        // Act
        $synchronizationDataTransferCollection = $this->tester->getFacade()->getSynchronizationDataTransferCollection(
            $paginationTransfer,
            [$customerTransfer1->getIdCustomerOrFail(), $customerTransfer2->getIdCustomerOrFail()],
        );

        // Assert
        $this->assertCount(2, $synchronizationDataTransferCollection);
        $this->assertInstanceOf(SynchronizationDataTransfer::class, $synchronizationDataTransferCollection[0]);
    }
}
