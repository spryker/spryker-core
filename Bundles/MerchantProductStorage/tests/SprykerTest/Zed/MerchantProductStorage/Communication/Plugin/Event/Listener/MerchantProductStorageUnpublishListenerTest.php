<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\MerchantProduct\Persistence\Map\SpyMerchantProductAbstractTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\MerchantProduct\Dependency\MerchantProductEvents;
use Spryker\Zed\MerchantProductStorage\Communication\Plugin\Event\Listener\MerchantProductStoragePublishListener;
use Spryker\Zed\MerchantProductStorage\Communication\Plugin\Event\Listener\MerchantProductStorageUnpublishListener;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group MerchantProductStorageUnpublishListenerTest
 * Add your own group annotations below this line
 */
class MerchantProductStorageUnpublishListenerTest extends Unit
{
    /**
     * @var \Spryker\Zed\MerchantProductStorage\Communication\Plugin\Event\Listener\MerchantProductStoragePublishListener
     */
    protected $merchantProductStoragePublishListener;

    /**
     * @var \Spryker\Zed\MerchantProductStorage\Communication\Plugin\Event\Listener\MerchantProductStorageUnpublishListener
     */
    protected $merchantProductStorageUnpublishListener;

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

        $this->merchantProductStoragePublishListener = new MerchantProductStoragePublishListener();
        $this->merchantProductStorageUnpublishListener = new MerchantProductStorageUnpublishListener();
    }

    /**
     * @return void
     */
    public function testMerchantProductStorageUnpublishListener(): void
    {
        //Arrange
        $merchantProductTransfer = $this->tester->haveMerchantProduct();

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
        $this->merchantProductStoragePublishListener->handleBulk(
            $publishEventTransfers,
            MerchantProductEvents::MERCHANT_PRODUCT_ABSTRACT_KEY_PUBLISH
        );
        $this->merchantProductStorageUnpublishListener->handleBulk(
            $unpublishEventTransfers,
            MerchantProductEvents::MERCHANT_PRODUCT_ABSTRACT_KEY_UNPUBLISH
        );
        $count = $this->tester->countMerchantProductAbstract($merchantProductTransfer->getIdProductAbstract());

        //Assert
        $this->assertSame($expectedCount, $count);
    }
}
