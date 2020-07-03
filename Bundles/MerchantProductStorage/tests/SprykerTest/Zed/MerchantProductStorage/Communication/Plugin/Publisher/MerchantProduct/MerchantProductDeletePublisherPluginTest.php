<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductStorage\Communication\Plugin\Publisher\MerchantProduct;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Orm\Zed\MerchantProduct\Persistence\Map\SpyMerchantProductAbstractTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\MerchantProduct\Dependency\MerchantProductEvents;
use Spryker\Zed\MerchantProductStorage\Communication\Plugin\Publisher\MerchantProduct\MerchantProductDeletePublisherPlugin;
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

        $this->merchantProductDeletePublisherPlugin = new MerchantProductWritePublisherPlugin();
        $this->merchantProductWritePublisherPlugin = new MerchantProductDeletePublisherPlugin();
    }

    /**
     * @return void
     */
    public function testMerchantProductStorageUnpublishListener(): void
    {
        //Arrange
        $merchant = $this->tester->haveMerchant();
        $productAbstract = $this->tester->haveProductAbstract();

        $merchantProductData = [
            MerchantProductTransfer::ID_MERCHANT => $merchant->getIdMerchant(),
            MerchantProductTransfer::ID_PRODUCT_ABSTRACT => $productAbstract->getIdProductAbstract(),
        ];
        $merchantProductTransfer = $this->tester->haveMerchantProduct($merchantProductData);

        $expectedCount = 0;
        $publishEventTransfers = [
            (new EventEntityTransfer())->setId($merchantProductTransfer->getIdProductAbstract()),
        ];

        $unpublishEventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([
                SpyMerchantProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT => $merchantProductTransfer->getIdProductAbstract(),
            ]),
        ];

        //Act
        $this->merchantProductDeletePublisherPlugin->handleBulk(
            $publishEventTransfers,
            MerchantProductEvents::MERCHANT_PRODUCT_ABSTRACT_PUBLISH
        );
        $this->merchantProductWritePublisherPlugin->handleBulk(
            $unpublishEventTransfers,
            MerchantProductEvents::MERCHANT_PRODUCT_ABSTRACT_UNPUBLISH
        );
        $count = $this->tester->countMerchantProductAbstract($merchantProductTransfer->getIdProductAbstract());

        //Assert
        $this->assertSame($expectedCount, $count);
    }
}
