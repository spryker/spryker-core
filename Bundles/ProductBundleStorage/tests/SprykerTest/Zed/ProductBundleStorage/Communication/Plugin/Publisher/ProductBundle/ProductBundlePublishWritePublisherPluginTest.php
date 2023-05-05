<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundleStorage\Communication\Plugin\Publisher\ProductBundle;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Shared\ProductBundleStorage\ProductBundleStorageConfig;
use Spryker\Zed\ProductBundleStorage\Communication\Plugin\Publisher\ProductBundle\ProductBundlePublishWritePublisherPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundleStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group ProductBundle
 * @group ProductBundlePublishWritePublisherPluginTest
 * Add your own group annotations below this line
 */
class ProductBundlePublishWritePublisherPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const FAKE_ID_PRODUCT_CONCRETE = 6666;

    /**
     * @var \SprykerTest\Zed\ProductBundleStorage\ProductBundleStorageCommunicationTester
     */
    protected $tester;

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
    public function testProductBundleWritePublisherPlugin(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProductBundle($this->tester->haveFullProduct());
        $productForBundleTransfers = $productConcreteTransfer->getProductBundle()->getBundledProducts();

        // Act
        $productBundleWritePublisherPlugin = new ProductBundlePublishWritePublisherPlugin();
        $eventTransfers = [
            (new EventEntityTransfer())->setId($productConcreteTransfer->getIdProductConcrete()),
        ];

        $productBundleWritePublisherPlugin->handleBulk(
            $eventTransfers,
            ProductBundleStorageConfig::PRODUCT_BUNDLE_PUBLISH,
        );

        // Assert
        $productBundleStorageTransfer = $this->tester
            ->findProductBundleStorageByFkProduct($productConcreteTransfer->getIdProductConcrete());
        $productForProductBundleStorageTransfers = $productBundleStorageTransfer->getBundledProducts();

        $this->assertSame(
            $productConcreteTransfer->getIdProductConcrete(),
            $productBundleStorageTransfer->getIdProductConcreteBundle(),
        );
        $this->assertAllProductBundlesExistInStorage($productForBundleTransfers, $productForProductBundleStorageTransfers);
    }

    /**
     * @return void
     */
    public function testProductBundleWritePublisherPluginWithSeveralIds(): void
    {
        // Arrange
        $firstProductConcreteTransfer = $this->tester->haveProductBundle($this->tester->haveFullProduct());
        $secondProductConcreteTransfer = $this->tester->haveProductBundle($this->tester->haveFullProduct());

        // Act
        $productBundleWritePublisherPlugin = new ProductBundlePublishWritePublisherPlugin();
        $eventTransfers = [
            (new EventEntityTransfer())->setId($firstProductConcreteTransfer->getIdProductConcrete()),
            (new EventEntityTransfer())->setId($secondProductConcreteTransfer->getIdProductConcrete()),
        ];

        $productBundleWritePublisherPlugin->handleBulk(
            $eventTransfers,
            ProductBundleStorageConfig::PRODUCT_BUNDLE_PUBLISH,
        );

        // Assert
        $firstProductBundleStorageTransfer = $this->tester
            ->findProductBundleStorageByFkProduct($firstProductConcreteTransfer->getIdProductConcrete());
        $secondProductBundleStorageTransfer = $this->tester
            ->findProductBundleStorageByFkProduct($secondProductConcreteTransfer->getIdProductConcrete());

        $this->assertSame(
            $firstProductConcreteTransfer->getIdProductConcrete(),
            $firstProductBundleStorageTransfer->getIdProductConcreteBundle(),
        );
        $this->assertSame(
            $secondProductConcreteTransfer->getIdProductConcrete(),
            $secondProductBundleStorageTransfer->getIdProductConcreteBundle(),
        );
    }

    /**
     * @return void
     */
    public function testProductBundleWritePublisherPluginWithFakeProductConcreteId(): void
    {
        // Act
        $productBundleWritePublisherPlugin = new ProductBundlePublishWritePublisherPlugin();
        $eventTransfers = [
            (new EventEntityTransfer())->setId(static::FAKE_ID_PRODUCT_CONCRETE),
        ];

        $productBundleWritePublisherPlugin->handleBulk(
            $eventTransfers,
            ProductBundleStorageConfig::PRODUCT_BUNDLE_PUBLISH,
        );

        // Assert
        $productBundleStorageTransfer = $this->tester
            ->findProductBundleStorageByFkProduct(static::FAKE_ID_PRODUCT_CONCRETE);

        $this->assertNull($productBundleStorageTransfer);
    }

    /**
     * @param \ArrayObject|array<\Generated\Shared\Transfer\ProductForBundleTransfer> $productForBundleTransfers
     * @param \ArrayObject|array<\Generated\Shared\Transfer\ProductForProductBundleStorageTransfer> $productForBundleStorageTransfers
     *
     * @return void
     */
    protected function assertAllProductBundlesExistInStorage(
        ArrayObject $productForBundleTransfers,
        ArrayObject $productForBundleStorageTransfers
    ): void {
        $foundTransfers = 0;
        foreach ($productForBundleTransfers as $productForBundleTransfer) {
            foreach ($productForBundleStorageTransfers as $productForBundleStorageTransfer) {
                if (
                    $productForBundleTransfer->getIdProductConcrete()
                    === $productForBundleStorageTransfer->getIdProductConcrete()
                ) {
                    $this->assertEquals(
                        $productForBundleStorageTransfer->getQuantity(),
                        $productForBundleTransfer->getQuantity(),
                        sprintf(
                            'The full product bundle data in database %s does not align with the full data in storage %s',
                            json_encode((array)$productForBundleTransfers),
                            json_encode((array)$productForBundleStorageTransfer),
                        ),
                    );

                    $foundTransfers++;

                    break;
                }
            }
        }

        if ($foundTransfers !== count($productForBundleStorageTransfers)) {
            $this->fail('Not all product bundles were found in storage');
        }
    }
}
