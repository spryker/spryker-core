<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerAccessStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\CustomerAccessTransfer;
use Orm\Zed\CustomerAccessStorage\Persistence\SpyUnauthenticatedCustomerAccessStorage;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStoragePersistenceFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CustomerAccessStorage
 * @group Business
 * @group Facade
 * @group CustomerAccessStorageFacadeTest
 * Add your own group annotations below this line
 */
class CustomerAccessStorageFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CustomerAccessStorage\CustomerAccessStorageBusinessTester
     */
    protected $tester;

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
    }

    /**
     * @return void
     */
    public function testPublishStoresEntityData(): void
    {
        $customerAccessTransfer = $this->tester->haveCustomerAccess(
            [
                CustomerAccessTransfer::CONTENT_TYPE_ACCESS => [
                    [
                        ContentTypeAccessTransfer::IS_RESTRICTED => true,
                        ContentTypeAccessTransfer::CONTENT_TYPE => 'price',
                    ],
                ],
            ]
        );
        $this->tester->getFacade()->publish();
        $customerAccessEntity = $this->getUnauthenticatedCustomerAccessEntity();

        $this->assertContains(json_encode($customerAccessTransfer->getContentTypeAccess()[0]->toArray()), json_encode($customerAccessEntity->getData()));
    }

    /**
     * @return \Orm\Zed\CustomerAccessStorage\Persistence\SpyUnauthenticatedCustomerAccessStorage
     */
    protected function getUnauthenticatedCustomerAccessEntity(): SpyUnauthenticatedCustomerAccessStorage
    {
        $persistenceFactory = new CustomerAccessStoragePersistenceFactory();
        $customerTransferStorageEntity = $persistenceFactory->createCustomerAccessStorageQuery()
            ->lastCreatedFirst()
            ->findOne();

        return $customerTransferStorageEntity;
    }
}
