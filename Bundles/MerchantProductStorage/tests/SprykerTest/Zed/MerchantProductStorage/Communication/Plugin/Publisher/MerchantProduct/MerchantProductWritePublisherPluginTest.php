<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductStorage\Communication\Plugin\Publisher\MerchantProduct;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\MerchantProduct\Dependency\MerchantProductEvents;
use Spryker\Zed\MerchantProductStorage\Communication\Plugin\Publisher\MerchantProduct\MerchantProductWritePublisherPlugin;

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
 * @group MerchantProductWritePublisherPluginTest
 * Add your own group annotations below this line
 */
class MerchantProductWritePublisherPluginTest extends Unit
{
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

        $this->merchantProductWritePublisherPlugin = new MerchantProductWritePublisherPlugin();
    }

    /**
     * @return void
     */
    public function testMerchantProductWritePublisher(): void
    {
        //Arrange
        $expectedCount = 1;

        $merchant = $this->tester->haveMerchant();
        $productAbstract = $this->tester->haveProductAbstract();

        $merchantProductData = [
            MerchantProductTransfer::ID_MERCHANT => $merchant->getIdMerchant(),
            MerchantProductTransfer::ID_PRODUCT_ABSTRACT => $productAbstract->getIdProductAbstract(),
        ];

        $merchantProductTransfer = $this->tester->haveMerchantProduct($merchantProductData);

        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantProductTransfer->getIdProductAbstract()),
        ];

        //Act
        $this->merchantProductWritePublisherPlugin->handleBulk(
            $eventTransfers,
            MerchantProductEvents::MERCHANT_PRODUCT_ABSTRACT_PUBLISH
        );
        $count = $this->tester->countMerchantProductAbstract($merchantProductTransfer->getIdProductAbstract());

        //Assert
        $this->assertSame($expectedCount, $count);
    }
}
