<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundleStorage\Communication\Plugin\Publisher\ProductConcrete;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Shared\ProductBundleStorage\ProductBundleStorageConfig;
use Spryker\Zed\ProductBundleStorage\Communication\Plugin\Publisher\ProductConcrete\ProductBundleWritePublisherPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundleStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group ProductConcrete
 * @group ProductBundleWritePublisherPluginTest
 * Add your own group annotations below this line
 */
class ProductBundleWritePublisherPluginTest extends Unit
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
    public function testBundledProductWritePublisherPlugin(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProductBundle($this->tester->haveFullProduct());
        $productForBundleTransfers = $productConcreteTransfer->getProductBundle()->getBundledProducts();

        // Act
        $productConcreteProductBundleWritePublisherPlugin = new ProductBundleWritePublisherPlugin();
        $eventTransfers = [
            (new EventEntityTransfer())
                ->setId($productConcreteTransfer->getIdProductConcrete())
                ->setModifiedColumns([SpyProductTableMap::COL_IS_ACTIVE]),
        ];

        $productConcreteProductBundleWritePublisherPlugin->handleBulk(
            $eventTransfers,
            ProductBundleStorageConfig::ENTITY_SPY_PRODUCT_UPDATE,
        );

        // Assert
        $productBundleStorageTransfer = $this->tester
            ->findProductBundleStorageByFkProduct($productConcreteTransfer->getIdProductConcrete());
        $productForBundleStorageTransfers = $productBundleStorageTransfer->getBundledProducts();

        $this->assertSame(
            $productConcreteTransfer->getIdProductConcrete(),
            $productBundleStorageTransfer->getIdProductConcreteBundle(),
        );
        $this->assertAllProductBundlesExistInStorage($productForBundleTransfers, $productForBundleStorageTransfers);
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

    /**
     * @return void
     */
    public function testBundledProductWritePublisherPluginWithSeveralIds(): void
    {
        // Arrange
        $firstProductConcreteTransfer = $this->tester->haveProductBundle($this->tester->haveFullProduct());
        $secondProductConcreteTransfer = $this->tester->haveProductBundle($this->tester->haveFullProduct());

        // Act
        $productConcreteProductBundleWritePublisherPlugin = new ProductBundleWritePublisherPlugin();
        $eventTransfers = [
            (new EventEntityTransfer())
                ->setId($firstProductConcreteTransfer->getIdProductConcrete())
                ->setModifiedColumns([SpyProductTableMap::COL_IS_ACTIVE]),
            (new EventEntityTransfer())
                ->setId($secondProductConcreteTransfer->getIdProductConcrete())
                ->setModifiedColumns([SpyProductTableMap::COL_IS_ACTIVE]),
        ];

        $productConcreteProductBundleWritePublisherPlugin->handleBulk(
            $eventTransfers,
            ProductBundleStorageConfig::ENTITY_SPY_PRODUCT_UPDATE,
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
    public function testBundledProductWritePublisherPluginWithFakeProductConcreteId(): void
    {
        // Act
        $productConcreteProductBundleWritePublisherPlugin = new ProductBundleWritePublisherPlugin();
        $eventTransfers = [
            (new EventEntityTransfer())
                ->setId(static::FAKE_ID_PRODUCT_CONCRETE)
                ->setModifiedColumns([SpyProductTableMap::COL_IS_ACTIVE]),
        ];

        $productConcreteProductBundleWritePublisherPlugin->handleBulk(
            $eventTransfers,
            ProductBundleStorageConfig::ENTITY_SPY_PRODUCT_UPDATE,
        );

        // Assert
        $productBundleStorageTransfer = $this->tester
            ->findProductBundleStorageByFkProduct(static::FAKE_ID_PRODUCT_CONCRETE);

        $this->assertNull($productBundleStorageTransfer);
    }
}
