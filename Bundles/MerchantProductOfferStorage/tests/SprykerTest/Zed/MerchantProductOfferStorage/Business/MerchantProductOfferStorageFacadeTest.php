<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductOfferServicesTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use SprykerTest\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOfferStorage
 * @group Business
 * @group Facade
 * @group MerchantProductOfferStorageFacadeTest
 * Add your own group annotations below this line
 */
class MerchantProductOfferStorageFacadeTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_DENIED
     *
     * @var string
     */
    protected const MERCHANT_STATUS_DENIED = 'denied';

    /**
     * @var \SprykerTest\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageTester
     */
    protected MerchantProductOfferStorageTester $tester;

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

        $this->tester->addDependencies();
    }

    /**
     * @return void
     */
    public function testWriteProductConcreteProductOffersStorageCollectionByMerchantEvents(): void
    {
        // Assign
        $this->tester->clearProductOfferData();
        $storeTransfer = $this->tester->getLocator()->store()->facade()->getCurrentStore();
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::IS_ACTIVE => true]);
        $productTransfer = $this->tester->haveProduct();

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ProductOfferTransfer::CONCRETE_SKU => $productTransfer->getSku(),
        ])->addStore($storeTransfer);

        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer);

        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantTransfer->getIdMerchant()),
        ];

        // Act
        $this->tester->getFacade()->writeProductConcreteProductOffersStorageCollectionByMerchantEvents($eventTransfers);

        $this->assertCount(
            1,
            $this->tester->getProductConcreteProductOffersEntities($productOfferTransfer->getConcreteSku()),
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByMerchantEvents(): void
    {
        // Arrange
        $this->tester->clearProductOfferData();
        $storeTransfer = $this->tester->getLocator()->store()->facade()->getCurrentStore();
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::IS_ACTIVE => true]);
        $productTransfer = $this->tester->haveProduct();

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ProductOfferTransfer::CONCRETE_SKU => $productTransfer->getSku(),
        ])->addStore($storeTransfer);

        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer);

        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantTransfer->getIdMerchant()),
        ];

        // Act
        $this->tester->getFacade()->writeCollectionByMerchantEvents($eventTransfers);

        // Assert
        $this->assertCount(
            1,
            $this->tester->getProductOfferEntities($productOfferTransfer->getProductOfferReference()),
        );
    }

    /**
     * @return void
     */
    public function testFilterProductOfferServicesShouldFilterOutProductOfferServicesWithInactiveMerchants(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::IS_ACTIVE => true,
            MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED,
        ]);
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReferenceOrFail(),
        ]);

        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::IS_ACTIVE => true,
            MerchantTransfer::STATUS => static::MERCHANT_STATUS_DENIED,
        ]);
        $secondProductOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReferenceOrFail(),
        ]);

        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::IS_ACTIVE => false,
            MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED,
        ]);
        $thirdProductOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReferenceOrFail(),
        ]);

        $productOfferServiceTransfers = [
            (new ProductOfferServicesTransfer())->setProductOffer($productOfferTransfer),
            (new ProductOfferServicesTransfer())->setProductOffer($secondProductOfferTransfer),
            (new ProductOfferServicesTransfer())->setProductOffer($thirdProductOfferTransfer),
            (new ProductOfferServicesTransfer())->setProductOffer(new ProductOfferTransfer()),
        ];

        // Act
        $productOfferServiceTransfers = $this->tester->getFacade()->filterProductOfferServices($productOfferServiceTransfers);

        // Assert
        $this->assertCount(1, $productOfferServiceTransfers);
        $this->assertSame($productOfferTransfer->getProductOfferReferenceOrFail(), $productOfferServiceTransfers[0]->getProductOfferOrFail()->getProductOfferReferenceOrFail());
    }
}
