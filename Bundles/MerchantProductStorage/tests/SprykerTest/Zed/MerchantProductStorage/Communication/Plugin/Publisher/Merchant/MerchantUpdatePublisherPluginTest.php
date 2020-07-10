<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductStorage\Communication\Plugin\Publisher\Merchant;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Shared\MerchantProductStorage\MerchantProductStorageConfig;
use Spryker\Zed\Kernel\Container as ZedContainer;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;
use Spryker\Zed\MerchantProductStorage\Communication\Plugin\ProductStorage\MerchantProductAbstractStorageExpanderPlugin;
use Spryker\Zed\MerchantProductStorage\Communication\Plugin\Publisher\Merchant\MerchantUpdatePublisherPlugin;
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
 * @group Merchant
 * @group MerchantUpdatePublisherPluginTest
 * Add your own group annotations below this line
 */
class MerchantUpdatePublisherPluginTest extends Unit
{
    /**
     * @var \Spryker\Zed\MerchantProductStorage\Communication\Plugin\Publisher\Merchant\MerchantUpdatePublisherPlugin
     */
    protected $merchantUpdatePublisherPlugin;

    /**
     * @var \Spryker\Zed\MerchantProductStorage\Communication\Plugin\Publisher\MerchantProduct\MerchantProductWritePublisherPlugin
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

        $this->merchantUpdatePublisherPlugin = new MerchantUpdatePublisherPlugin();
        $this->merchantProductWritePublisherPlugin = new MerchantProductWritePublisherPlugin();
    }

    /**
     * @return void
     */
    public function testMerchantUpdatePublisherPlugin(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $productAbstractTransfer = $this->tester->haveFullProduct();

        $merchantProductData = [
            MerchantProductTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantProductTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getFkProductAbstract(),
        ];

        $merchantProductTransfer = $this->tester->haveMerchantProduct($merchantProductData);

        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantProductTransfer->getIdMerchantProductAbstract()),
        ];

        // Act
        $this->merchantProductWritePublisherPlugin->handleBulk(
            $eventTransfers,
            MerchantProductStorageConfig::MERCHANT_PRODUCT_ABSTRACT_PUBLISH
        );

        $merchantTransfer->setIsActive(false);
        $this->tester->getMerchantFacade()->updateMerchant($merchantTransfer);
        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantTransfer->getIdMerchant()),
        ];
        $this->merchantUpdatePublisherPlugin->handleBulk(
            $eventTransfers,
            MerchantEvents::ENTITY_SPY_MERCHANT_UPDATE
        );
        $productAbstractStorageEntityWithoutMerchant = $this->tester->getAbstractProductStorageByIdProductAbstract(
            $merchantProductTransfer->getIdProductAbstract()
        );

        // Assert
        $this->assertNull($productAbstractStorageEntityWithoutMerchant->getData()['merchant_reference']);
    }
}
