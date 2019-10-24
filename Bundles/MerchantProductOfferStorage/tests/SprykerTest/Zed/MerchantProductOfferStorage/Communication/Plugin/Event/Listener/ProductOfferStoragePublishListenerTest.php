<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\MerchantProductOffer\Dependency\MerchantProductOfferEvents;
use Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener\ProductOfferStoragePublishListener;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOfferStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductOfferStoragePublishListenerTest
 * Add your own group annotations below this line
 */
class ProductOfferStoragePublishListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected $merchantProductOfferTransfer;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener\ProductOfferStoragePublishListener
     */
    protected $productOfferStoragePublishListener;

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

        $this->merchantProductOfferTransfer = $this->tester->haveMerchantProductOffer([
            'fkMerchant' => $this->tester->haveMerchant()->getIdMerchant(),
        ]);

        $this->productOfferStoragePublishListener = new ProductOfferStoragePublishListener();
    }

    /**
     * @return void
     */
    public function testProductOfferStoragePublishListener(): void
    {
        //Arrange
        $expectedCount = 1;
        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->merchantProductOfferTransfer->getIdProductOffer()),
        ];

        //Act
        $this->productOfferStoragePublishListener->handleBulk(
            $eventTransfers,
            MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_KEY_PUBLISH
        );
        $merchantProductOfferStorageEntities = $this->tester->findProductOfferEntities($this->merchantProductOfferTransfer->getProductOfferReference());

        //Assert
        $this->assertCount($expectedCount, $merchantProductOfferStorageEntities);
    }

    /**
     * @return void
     */
    protected function _after(): void
    {
        parent::_after();

        $this->cleanUpProductOfferStorage();
    }

    /**
     * @return void
     */
    protected function cleanUpProductOfferStorage(): void
    {
        $merchantProductOfferStorageEntities = $this->tester->findProductOfferEntities($this->merchantProductOfferTransfer->getProductOfferReference());

        foreach ($merchantProductOfferStorageEntities as $merchantProductOfferStorageEntity) {
            $merchantProductOfferStorageEntity->delete();
        }
    }
}
