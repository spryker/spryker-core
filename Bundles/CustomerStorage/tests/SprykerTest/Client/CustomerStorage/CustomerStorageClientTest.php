<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\CustomerStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerTransfer;
use Spryker\Client\CustomerStorage\CustomerStorageDependencyProvider;
use Spryker\Client\CustomerStorage\Dependency\Client\CustomerStorageToStorageClientInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group CustomerStorage
 * @group CustomerStorageClientTest
 * Add your own group annotations below this line
 */
class CustomerStorageClientTest extends Unit
{
    /**
     * @var string
     */
    protected const CUSTOMER_REFERENCE_1 = 'TEST--1';

    /**
     * @var string
     */
    protected const CUSTOMER_REFERENCE_2 = 'TEST--2';

    /**
     * @var string
     */
    protected const CUSTOMER_STORAGE_KEY_1 = 'customer_invalidated:test--1';

    /**
     * @var string
     */
    protected const CUSTOMER_STORAGE_KEY_2 = 'customer_invalidated:test--2';

    /**
     * @var \SprykerTest\Client\CustomerStorage\CustomerStorageClientTester
     */
    protected CustomerStorageClientTester $tester;

    /**
     * @return void
     */
    public function testGetInvalidatedCustomerCollectionReturnsInvalidatedCustomerCollectionTransfer(): void
    {
        // Arrange
        $this->createStorageClientMock();

        // Act
        $invalidatedCustomerCollectionTransfer = $this->tester->getCustomerStorageClient()
            ->getInvalidatedCustomerCollection($this->tester->createInvalidatedCustomerCriteriaTransfer());

        // Assert
        $this->assertInstanceOf(InvalidatedCustomerCollectionTransfer::class, $invalidatedCustomerCollectionTransfer);
        $this->assertCount(2, $invalidatedCustomerCollectionTransfer->getInvalidatedCustomers());
    }

    /**
     * @return void
     */
    protected function createStorageClientMock(): void
    {
        $invalidatedCustomerTransfer1 = (new InvalidatedCustomerTransfer())
            ->setCustomerReference(static::CUSTOMER_REFERENCE_1);
        $invalidatedCustomerTransfer2 = (new InvalidatedCustomerTransfer())
            ->setCustomerReference(static::CUSTOMER_REFERENCE_2);

        $storageClientMock = $this->getMockBuilder(CustomerStorageToStorageClientInterface::class)->getMock();
        $storageClientMock->expects($this->once())
            ->method('getMulti')
            ->willReturn([
                static::CUSTOMER_STORAGE_KEY_1 => json_encode($invalidatedCustomerTransfer1->toArray()),
                static::CUSTOMER_STORAGE_KEY_2 => json_encode($invalidatedCustomerTransfer2->toArray()),
            ]);

        $this->tester->setDependency(CustomerStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);
    }
}
