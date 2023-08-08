<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageImageSetProductImageSearchListener;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchBridge;
use Spryker\Zed\ProductPageSearch\ProductPageSearchDependencyProvider;
use SprykerTest\Zed\ProductPageSearch\ProductPageSearchCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPageSearch
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductPageImageSetProductImageSearchListenerTest
 * Add your own group annotations below this line
 */
class ProductPageImageSetProductImageSearchListenerTest extends Unit
{
    /**
     * @uses \Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetToProductImageTableMap::COL_FK_PRODUCT_IMAGE_SET
     *
     * @var string
     */
    protected const COL_FK_PRODUCT_IMAGE_SET = 'spy_product_image_set_to_product_image.fk_product_image_set';

    /**
     * @var string
     */
    protected const INDEX_ID_PRODUCT_IMAGE = 'id_product_image';

    /**
     * @var \SprykerTest\Zed\ProductPageSearch\ProductPageSearchCommunicationTester
     */
    protected ProductPageSearchCommunicationTester $tester;

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

        $this->tester->setDependency(ProductPageSearchDependencyProvider::FACADE_SEARCH, Stub::make(
            ProductPageSearchToSearchBridge::class,
            [
                'transformPageMapToDocumentByMapperName' => function () {
                    return [];
                },
            ],
        ));
    }

    /**
     * @return void
     */
    public function testPublishesData(): void
    {
        // Arrange
        $storeTransfer = $this->tester->getLocator()->store()->facade()->getCurrentStore();
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->getLocator()->productSearch()->facade()->activateProductSearch(
            $productConcreteTransfer->getIdProductConcreteOrFail(),
            [
                $this->tester->getLocator()->locale()->facade()->getCurrentLocale(),
            ],
        );

        $productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstractOrFail(),
        ]);

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                static::COL_FK_PRODUCT_IMAGE_SET => $productImageSetTransfer->getIdProductImageSetOrFail(),
            ]),
        ];

        $productPageImageSetProductImageSearchListener = new ProductPageImageSetProductImageSearchListener();
        $productPageImageSetProductImageSearchListener->setFacade($this->tester->getFacade());

        // Act
        $productPageImageSetProductImageSearchListener->handleBulk($eventEntityTransfers, '');

        // Assert
        $productPageSearchTransfer = $this->tester->findProductPageSearchTransfer(
            $productConcreteTransfer->getFkProductAbstractOrFail(),
            $storeTransfer->getNameOrFail(),
        );

        $this->assertNotNull($productPageSearchTransfer);
        $this->assertCount(count($productImageSetTransfer->getProductImages()), $productPageSearchTransfer->getProductImages());
        $this->assertSame(
            $productImageSetTransfer->getProductImages()[0]->getIdProductImageOrFail(),
            $productPageSearchTransfer->getProductImages()[0][static::INDEX_ID_PRODUCT_IMAGE],
        );
    }
}
