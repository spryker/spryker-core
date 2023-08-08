<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImageStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\ProductImageStorage\Business\ProductImageStorageBusinessFactory;
use Spryker\Zed\ProductImageStorage\Business\ProductImageStorageFacade;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageSetProductImageStorageUnpublishListener;
use SprykerTest\Zed\ProductImageStorage\ProductImageStorageCommunicationTester;
use SprykerTest\Zed\ProductImageStorage\ProductImageStorageConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImageStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductAbstractImageSetProductImageStorageUnpublishListenerTest
 * Add your own group annotations below this line
 */
class ProductAbstractImageSetProductImageStorageUnpublishListenerTest extends Unit
{
    /**
     * @uses \Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetToProductImageTableMap::COL_FK_PRODUCT_IMAGE_SET
     *
     * @var string
     */
    protected const COL_FK_PRODUCT_IMAGE_SET = 'spy_product_image_set_to_product_image.fk_product_image_set';

    /**
     * @var \SprykerTest\Zed\ProductImageStorage\ProductImageStorageCommunicationTester
     */
    protected ProductImageStorageCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
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
    public function testPublishesData(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstractOrFail(),
        ]);

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                static::COL_FK_PRODUCT_IMAGE_SET => $productImageSetTransfer->getIdProductImageSetOrFail(),
            ]),
        ];

        $productAbstractImageSetProductImageStorageUnpublishListener = new ProductAbstractImageSetProductImageStorageUnpublishListener();
        $productAbstractImageSetProductImageStorageUnpublishListener->setFacade($this->getProductImageStorageFacade());

        // Act
        $productAbstractImageSetProductImageStorageUnpublishListener->handleBulk($eventEntityTransfers, '');
        $productAbstractImageStorageTransfer = $this->tester->findProductAbstractImageStorageTransfer($productConcreteTransfer->getFkProductAbstractOrFail());

        // Assert
        $this->assertNotNull($productAbstractImageStorageTransfer);
        $this->assertCount(1, $productAbstractImageStorageTransfer->getImageSets());
        $this->assertCount(count($productImageSetTransfer->getProductImages()), $productAbstractImageStorageTransfer->getImageSets()[0]->getImages());
        $this->assertSame(
            $productImageSetTransfer->getProductImages()[0]->getIdProductImageOrFail(),
            $productAbstractImageStorageTransfer->getImageSets()[0]->getImages()[0]->getIdProductImageOrFail(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductImageStorage\Business\ProductImageStorageFacade
     */
    protected function getProductImageStorageFacade(): ProductImageStorageFacade
    {
        $factory = new ProductImageStorageBusinessFactory();
        $factory->setConfig(new ProductImageStorageConfigMock());

        $facade = new ProductImageStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }
}
