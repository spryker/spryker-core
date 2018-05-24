<?php

namespace SprykerTest\Zed\CustomerAccess\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\CustomerAccessTransfer;
use Orm\Zed\CustomerAccessStorage\Persistence\SpyUnauthenticatedCustomerAccessStorage;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStoragePersistenceFactory;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CustomerAccess
 * @group Business
 * @group Facade
 * @group CustomerAccessStorageFacadeTest
 * Add your own group annotations below this line
 */
class CustomerAccessStorageFacadeTest extends Unit
{
    /**
     * @return void
     */
    public function setUp()
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
                        ContentTypeAccessTransfer::HAS_ACCESS => false,
                        ContentTypeAccessTransfer::CONTENT_TYPE => 'price',
                    ],
                ],
            ]
        );
        $this->tester->getFacade()->publish();
        $customerAccessEntity = $this->getUnauthenticatedCustomerAccessEntity();

        $this->assertEquals($customerAccessTransfer->toArray(), $customerAccessEntity->getData());
    }

    /**
     * @return \Orm\Zed\CustomerAccessStorage\Persistence\SpyUnauthenticatedCustomerAccessStorage
     */
    protected function getUnauthenticatedCustomerAccessEntity(): SpyUnauthenticatedCustomerAccessStorage
    {
        $persistenceFactory = new CustomerAccessStoragePersistenceFactory();
        $customerTransferStorageEntity = $persistenceFactory->createPropelCustomerAccessStorageQuery()
            ->lastCreatedFirst()
            ->findOne();

        return $customerTransferStorageEntity;
    }
}
