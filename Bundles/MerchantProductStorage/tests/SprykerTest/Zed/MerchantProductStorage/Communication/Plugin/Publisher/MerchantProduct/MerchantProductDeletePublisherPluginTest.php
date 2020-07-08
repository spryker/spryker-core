<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductStorage\Communication\Plugin\Publisher\MerchantProduct;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\MerchantProduct\Persistence\Map\SpyMerchantProductAbstractTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Kernel\Container as ZedContainer;
use Spryker\Zed\MerchantProduct\Dependency\MerchantProductEvents;
use Spryker\Zed\MerchantProductStorage\Communication\Plugin\ProductStorage\MerchantProductAbstractStorageExpanderPlugin;
use Spryker\Zed\MerchantProductStorage\Communication\Plugin\Publisher\MerchantProduct\MerchantProductDeletePublisherPlugin;
use Spryker\Zed\MerchantProductStorage\Communication\Plugin\Publisher\MerchantProduct\MerchantProductWritePublisherPlugin;
use Spryker\Zed\ProductStorage\ProductStorageDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group MerchantProduct
 * @group MerchantProductDeletePublisherPluginTest
 * Add your own group annotations below this line
 */
class MerchantProductDeletePublisherPluginTest extends Unit
{
    /**
     * @var \Spryker\Zed\MerchantProductStorage\Communication\Plugin\Publisher\MerchantProduct\MerchantProductWritePublisherPlugin
     */
    protected $merchantProductDeletePublisherPlugin;

    /**
     * @var \Spryker\Zed\MerchantProductStorage\Communication\Plugin\Publisher\MerchantProduct\MerchantProductDeletePublisherPlugin
     */
    protected $merchantProductWritePublisherPlugin;

    /**
     * @var \SprykerTest\Zed\MerchantProductStorage\MerchantProductStorageTester
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

        $this->tester->setDependency(ProductStorageDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_STORAGE_EXPANDER, function (ZedContainer $container) {
            return [
                new MerchantProductAbstractStorageExpanderPlugin(),
            ];
        });

        $this->merchantProductDeletePublisherPlugin = new MerchantProductWritePublisherPlugin();
        $this->merchantProductWritePublisherPlugin = new MerchantProductDeletePublisherPlugin();
    }

    /**
     * @return void
     */
    public function testMerchantProductDeletePublisherPlugin(): void
    {
        // Arrange
        $productAbstract = $this->tester->haveFullProduct();

        $unpublishEventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([
                SpyMerchantProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT => $productAbstract->getFkProductAbstract(),
            ]),
        ];

        // Act
        $this->merchantProductWritePublisherPlugin->handleBulk(
            $unpublishEventTransfers,
            MerchantProductEvents::MERCHANT_PRODUCT_ABSTRACT_UNPUBLISH
        );
        $productAbstractStorage = $this->tester->getAbstractProductStorageByIdProductAbstract($productAbstract->getFkProductAbstract());

        // Assert
        $this->assertNull($productAbstractStorage->getData()['merchant_reference']);
    }
}
