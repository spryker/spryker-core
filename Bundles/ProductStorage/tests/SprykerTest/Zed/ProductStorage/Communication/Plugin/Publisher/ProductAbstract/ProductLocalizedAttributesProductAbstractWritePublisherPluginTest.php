<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductStorage\Communication\Plugin\Publisher\ProductAbstract;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductStorage\Communication\Plugin\Publisher\ProductAbstract\ProductLocalizedAttributesProductAbstractWritePublisherPlugin;
use SprykerTest\Zed\ProductStorage\ProductStorageCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group ProductAbstract
 * @group ProductLocalizedAttributesProductAbstractWritePublisherPluginTest
 * Add your own group annotations below this line
 */
class ProductLocalizedAttributesProductAbstractWritePublisherPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\ProductStorage\Business\Publisher\ProductAbstractStoragePublisher::COL_FK_PRODUCT
     *
     * @var string
     */
    protected const COL_FK_PRODUCT = 'spy_product_localized_attributes.fk_product';

    /**
     * @var \SprykerTest\Zed\ProductStorage\ProductStorageCommunicationTester
     */
    protected ProductStorageCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $this->tester->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
        $this->tester->ensureProductAbstractStorageTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testHandleBulkStoresProductAbstractStorageEntity(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_FK_PRODUCT => $productConcreteTransfer->getIdProductConcreteOrFail(),
        ]);

        // Act
        (new ProductLocalizedAttributesProductAbstractWritePublisherPlugin())->handleBulk(
            [$eventEntityTransfer],
            ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_UPDATE,
        );

        // Assert
        $this->assertNotNull($this->tester->findProductAbstractStorageEntityByIdProductAbstract(
            $productConcreteTransfer->getFkProductAbstractOrFail(),
        ));
    }

    /**
     * @return void
     */
    public function testHandleBulkDoesNothingWhenIdProductConcreteIsNotPassedInEventEntityTransfer(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            'testKey' => $productConcreteTransfer->getIdProductConcreteOrFail(),
        ]);

        // Act
        (new ProductLocalizedAttributesProductAbstractWritePublisherPlugin())->handleBulk(
            [$eventEntityTransfer],
            ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_UPDATE,
        );

        // Assert
        $this->assertSame(0, $this->tester->countProductAbstractStorageEntities());
    }

    /**
     * @return void
     */
    public function testHandleBulkDoesNothingWhenProductAbstractDoesNotExist(): void
    {
        // Arrange
        $this->tester->ensureProductAbstractTableIsEmpty();
        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_FK_PRODUCT => 1,
        ]);

        // Act
        (new ProductLocalizedAttributesProductAbstractWritePublisherPlugin())->handleBulk(
            [$eventEntityTransfer],
            ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_UPDATE,
        );

        // Assert
        $this->assertSame(0, $this->tester->countProductAbstractStorageEntities());
    }
}
