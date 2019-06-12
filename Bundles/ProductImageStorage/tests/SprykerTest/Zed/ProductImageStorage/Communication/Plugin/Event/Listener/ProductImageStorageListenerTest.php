<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImageStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorageQuery;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorageQuery;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;
use Spryker\Zed\ProductImageStorage\Business\ProductImageStorageBusinessFactory;
use Spryker\Zed\ProductImageStorage\Business\ProductImageStorageFacade;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageSetProductImageStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageSetProductImageStoragePublishListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageSetProductImageStorageUnpublishListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageSetStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageSetStoragePublishListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageSetStorageUnpublishListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageStoragePublishListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageStorageUnpublishListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductConcreteImageSetProductImageStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductConcreteImageSetProductImageStoragePublishListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductConcreteImageSetProductImageStorageUnpublishListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductConcreteImageSetStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductConcreteImageSetStoragePublishListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductConcreteImageSetStorageUnpublishListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductConcreteImageStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductConcreteImageStoragePublishListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductConcreteImageStorageUnpublishListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductImageAbstract\ProductImageAbstractStoragePublishListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductImageAbstract\ProductImageAbstractStorageUnpublishListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductImageAbstractPublishStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductImageConcrete\ProductImageConcreteStoragePublishListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductImageConcrete\ProductImageConcreteStorageUnpublishListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductImageConcretePublishStorageListener;
use SprykerTest\Shared\ProductImage\Helper\ProductImageDataHelper;
use SprykerTest\Zed\ProductImageStorage\ProductImageStorageConfigMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductImageStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductImageStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductImageStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductImageStorage\ProductImageStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $productAbstractTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    protected $productImageSetTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @throws \PHPUnit\Framework\SkippedTestError
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        if (!$this->tester->isSuiteProject()) {
            throw new SkippedTestError('Warning: not in suite environment');
        }

        $this->createProducts();

        $this->productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ProductImageSetTransfer::ID_PRODUCT => $this->productConcreteTransfer->getIdProductConcrete(),
        ]);
    }

    /**
     * @return void
     */
    protected function createProducts(): void
    {
        $this->productAbstractTransfer = $this->tester->haveProductAbstract();
        $this->productConcreteTransfer = $this->tester->haveProduct();

        $localizedAttributes = $this->tester->generateLocalizedAttributes();

        $this->tester->addLocalizedAttributesToProductAbstract($this->productAbstractTransfer, $localizedAttributes);
        $this->tester->addLocalizedAttributesToProductConcrete($this->productConcreteTransfer, $localizedAttributes);
    }

    /**
     * @return void
     */
    public function testProductImageAbstractPublishStorageListenerStoreData(): void
    {
        // Prepare
        SpyProductAbstractImageStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractImageStorageQuery::create()->count();

        $productImageAbstractPublishStorageListener = new ProductImageAbstractPublishStorageListener();
        $productImageAbstractPublishStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];

        // Act
        $productImageAbstractPublishStorageListener->handleBulk($eventTransfers, ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_PUBLISH);

        // Assert
        $this->assertProductAbstractImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductImageAbstractStoragePublishListenerStoreData(): void
    {
        // Prepare
        SpyProductAbstractImageStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractImageStorageQuery::create()->count();

        $productImageAbstractStoragePublishListener = new ProductImageAbstractStoragePublishListener();
        $productImageAbstractStoragePublishListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];

        // Act
        $productImageAbstractStoragePublishListener->handleBulk($eventTransfers, ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_PUBLISH);

        // Assert
        $this->assertProductAbstractImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductImageAbstractStorageUnpublishListenerStoreData(): void
    {
        // Prepare
        $productImageAbstractUnpublishStorageListener = new ProductImageAbstractStorageUnpublishListener();
        $productImageAbstractUnpublishStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];

        // Act
        $productImageAbstractUnpublishStorageListener->handleBulk($eventTransfers, ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_PUBLISH);

        // Assert
        $this->assertSame(0, SpyProductAbstractImageStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->count());
    }

    /**
     * @return void
     */
    public function testProductAbstractImageStorageListenerStoreData(): void
    {
        // Prepare
        SpyProductAbstractImageStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractImageStorageQuery::create()->count();

        $productImageAbstractPublishStorageListener = new ProductAbstractImageStorageListener();
        $productImageAbstractPublishStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [];

        foreach ($this->productImageSetTransfer->getProductImages() as $productImageTransfer) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($productImageTransfer->getIdProductImage());
        }

        // Act
        $productImageAbstractPublishStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE);

        // Assert
        $this->assertProductAbstractImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductAbstractImageEntityStoragePublishListener(): void
    {
        // Prepare
        SpyProductAbstractImageStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractImageStorageQuery::create()->count();

        $productImageAbstractPublishStorageListener = new ProductAbstractImageStoragePublishListener();
        $productImageAbstractPublishStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [];

        foreach ($this->productImageSetTransfer->getProductImages() as $productImageTransfer) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($productImageTransfer->getIdProductImage());
        }

        // Act
        $productImageAbstractPublishStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE);

        // Assert
        $this->assertProductAbstractImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductAbstractImageEntityStorageUnpublishListener(): void
    {
        // Prepare
        $productImageAbstractUnpublishStorageListener = new ProductAbstractImageStorageUnpublishListener();
        $productImageAbstractUnpublishStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [];

        foreach ($this->productImageSetTransfer->getProductImages() as $productImageTransfer) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($productImageTransfer->getIdProductImage());
        }

        // Act
        $productImageAbstractUnpublishStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_DELETE);

        // Assert
        $this->assertSame(0, SpyProductAbstractImageStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->count());
    }

    /**
     * @return void
     */
    public function testProductAbstractImageSetStorageListenerStoreData(): void
    {
        // Prepare
        SpyProductAbstractImageStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractImageStorageQuery::create()->count();

        $productAbstractImageSetStorageListener = new ProductAbstractImageSetStorageListener();
        $productAbstractImageSetStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];

        // Act
        $productAbstractImageSetStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE);

        // Assert
        $this->assertProductAbstractImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductAbstractImageSetStoragePublishListener(): void
    {
        // Prepare
        SpyProductAbstractImageStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractImageStorageQuery::create()->count();

        $productAbstractImageSetStoragePublishListener = new ProductAbstractImageSetStoragePublishListener();
        $productAbstractImageSetStoragePublishListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];

        // Act
        $productAbstractImageSetStoragePublishListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE);

        // Assert
        $this->assertProductAbstractImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductAbstractImageSetStorageUnpublishListener(): void
    {
        // Prepare
        $productAbstractImageSetStorageUnpublishListener = new ProductAbstractImageSetStorageUnpublishListener();
        $productAbstractImageSetStorageUnpublishListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];

        // Act
        $productAbstractImageSetStorageUnpublishListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_DELETE);

        // Assert
        $this->assertSame(0, SpyProductAbstractImageStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->count());
    }

    /**
     * @return void
     */
    public function testProductAbstractImageSetProductImageStorageListenerStoreData(): void
    {
        // Prepare
        SpyProductAbstractImageStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractImageStorageQuery::create()->count();

        $productAbstractImageSetProductImageStorageListener = new ProductAbstractImageSetProductImageStorageListener();
        $productAbstractImageSetProductImageStorageListener->setFacade($this->getProductImageStorageFacade());

        $productImageSetToProductImage = SpyProductImageSetToProductImageQuery::create()->findOneByFkProductImageSet(
            $this->productImageSetTransfer->getIdProductImageSet()
        );

        $eventTransfers = [];

        if ($productImageSetToProductImage) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($productImageSetToProductImage->getIdProductImageSetToProductImage());
        }

        // Act
        $productAbstractImageSetProductImageStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE);

        // Assert
        $this->assertProductAbstractImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductAbstractImageSetProductImageStoragePublishListener(): void
    {
        // Prepare
        SpyProductAbstractImageStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractImageStorageQuery::create()->count();

        $productAbstractImageSetProductImageStoragePublishListener = new ProductAbstractImageSetProductImageStoragePublishListener();
        $productAbstractImageSetProductImageStoragePublishListener->setFacade($this->getProductImageStorageFacade());

        $productImageSetToProductImage = SpyProductImageSetToProductImageQuery::create()->findOneByFkProductImageSet(
            $this->productImageSetTransfer->getIdProductImageSet()
        );

        $eventTransfers = [];

        if ($productImageSetToProductImage) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($productImageSetToProductImage->getIdProductImageSetToProductImage());
        }

        // Act
        $productAbstractImageSetProductImageStoragePublishListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE);

        // Assert
        $this->assertProductAbstractImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductAbstractImageSetProductImageStorageUnpublishListener(): void
    {
        // Prepare
        $productAbstractImageSetProductImageStorageUnpublishListener = new ProductAbstractImageSetProductImageStorageUnpublishListener();
        $productAbstractImageSetProductImageStorageUnpublishListener->setFacade($this->getProductImageStorageFacade());

        $productImageSetToProductImage = SpyProductImageSetToProductImageQuery::create()->findOneByFkProductImageSet(
            $this->productImageSetTransfer->getIdProductImageSet()
        );

        $eventTransfers = [];

        if ($productImageSetToProductImage) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($productImageSetToProductImage->getIdProductImageSetToProductImage());
        }

        // Act
        $productAbstractImageSetProductImageStorageUnpublishListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_DELETE);

        // Assert
        $this->assertSame(0, SpyProductAbstractImageStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->count());
    }

    /**
     * @return void
     */
    public function testProductImageConcretePublishStorageListenerStoreData()
    {
        // Prepare
        SpyProductConcreteImageStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->delete();
        $beforeCount = SpyProductConcreteImageStorageQuery::create()->count();

        $productImageConcretePublishStorageListener = new ProductImageConcretePublishStorageListener();
        $productImageConcretePublishStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productConcreteTransfer->getIdProductConcrete()),
        ];

        // Act
        $productImageConcretePublishStorageListener->handleBulk($eventTransfers, ProductImageEvents::PRODUCT_IMAGE_PRODUCT_CONCRETE_PUBLISH);

        // Assert
        $this->assertProductConcreteImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductImageConcreteStoragePublishListenerStoreData(): void
    {
        // Prepare
        SpyProductConcreteImageStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->delete();
        $beforeCount = SpyProductConcreteImageStorageQuery::create()->count();

        $productImageConcreteStoragePublishListener = new ProductImageConcreteStoragePublishListener();
        $productImageConcreteStoragePublishListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productConcreteTransfer->getIdProductConcrete()),
        ];

        // Act
        $productImageConcreteStoragePublishListener->handleBulk($eventTransfers, ProductImageEvents::PRODUCT_IMAGE_PRODUCT_CONCRETE_PUBLISH);

        // Assert
        $this->assertProductConcreteImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductImageConcreteStorageUnpublishListenerStoreData(): void
    {
        // Prepare
        $productImageConcreteStorageUnpublishListener = new ProductImageConcreteStorageUnpublishListener();
        $productImageConcreteStorageUnpublishListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productConcreteTransfer->getIdProductConcrete()),
        ];

        SpyProductLocalizedAttributesQuery::create()->findOneByFkProduct(
            $this->productConcreteTransfer->getIdProductConcrete()
        )->delete();

        // Act
        $productImageConcreteStorageUnpublishListener->handleBulk($eventTransfers, ProductImageEvents::PRODUCT_IMAGE_PRODUCT_CONCRETE_PUBLISH);

        // Assert
        $this->assertSame(0, SpyProductConcreteImageStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->count());
    }

    /**
     * @return void
     */
    public function testProductConcreteImageStorageListenerStoreData(): void
    {
        SpyProductConcreteImageStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->delete();
        $beforeCount = SpyProductConcreteImageStorageQuery::create()->count();

        $productConcreteImageStorageListener = new ProductConcreteImageStorageListener();
        $productConcreteImageStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [];

        foreach ($this->productImageSetTransfer->getProductImages() as $productImageTransfer) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($productImageTransfer->getIdProductImage());
        }

        $productConcreteImageStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE);

        // Assert
        $this->assertProductConcreteImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteImageStoragePublishListener(): void
    {
        // Prepare
        SpyProductConcreteImageStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->delete();
        $beforeCount = SpyProductConcreteImageStorageQuery::create()->count();

        $productConcreteImageStoragePublishListener = new ProductConcreteImageStoragePublishListener();
        $productConcreteImageStoragePublishListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [];

        foreach ($this->productImageSetTransfer->getProductImages() as $productImageTransfer) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($productImageTransfer->getIdProductImage());
        }

        // Act
        $productConcreteImageStoragePublishListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE);

        // Assert
        $this->assertProductConcreteImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteImageStorageUnpublishListener(): void
    {
        // Prepare
        $productConcreteImageStorageUnpublishListener = new ProductConcreteImageStorageUnpublishListener();
        $productConcreteImageStorageUnpublishListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [];

        foreach ($this->productImageSetTransfer->getProductImages() as $productImageTransfer) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($productImageTransfer->getIdProductImage());
        }

        SpyProductImageSetToProductImageQuery::create()->findOneByFkProductImageSet(
            $this->productImageSetTransfer->getIdProductImageSet()
        )->delete();

        // Act
        $productConcreteImageStorageUnpublishListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_DELETE);

        // Assert
        $this->assertSame(0, SpyProductConcreteImageStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->count());
    }

    /**
     * @return void
     */
    public function testProductConcreteImageSetStorageListenerStoreData(): void
    {
        SpyProductConcreteImageStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->delete();
        $beforeCount = SpyProductConcreteImageStorageQuery::create()->count();

        $productConcreteImageSetStorageListener = new ProductConcreteImageSetStorageListener();
        $productConcreteImageSetStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_PRODUCT => $this->productConcreteTransfer->getIdProductConcrete(),
            ]),
        ];
        $productConcreteImageSetStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE);

        // Assert
        $this->assertProductConcreteImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteImageSetStoragePublishListener(): void
    {
        // Prepare
        SpyProductConcreteImageStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->delete();
        $beforeCount = SpyProductConcreteImageStorageQuery::create()->count();

        $productConcreteImageSetStoragePublishListener = new ProductConcreteImageSetStoragePublishListener();
        $productConcreteImageSetStoragePublishListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_PRODUCT => $this->productConcreteTransfer->getIdProductConcrete(),
            ]),
        ];

        // Act
        $productConcreteImageSetStoragePublishListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE);

        // Assert
        $this->assertProductConcreteImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteImageSetStorageUnpublishListener(): void
    {
        // Prepare
        $productConcreteImageSetStorageListener = new ProductConcreteImageSetStorageUnpublishListener();
        $productConcreteImageSetStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_PRODUCT => $this->productConcreteTransfer->getIdProductConcrete(),
            ]),
        ];

        SpyProductLocalizedAttributesQuery::create()->findOneByFkProduct(
            $this->productConcreteTransfer->getIdProductConcrete()
        )->delete();

        // Act
        $productConcreteImageSetStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_DELETE);

        // Assert
        $this->assertSame(0, SpyProductConcreteImageStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->count());
    }

    /**
     * @return void
     */
    public function testProductConcreteImageSetProductImageStorageListenerStoreData(): void
    {
        // Prepare
        SpyProductConcreteImageStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->delete();
        $beforeCount = SpyProductConcreteImageStorageQuery::create()->count();

        $productConcreteImageSetProductImageStorageListener = new ProductConcreteImageSetProductImageStorageListener();
        $productConcreteImageSetProductImageStorageListener->setFacade($this->getProductImageStorageFacade());

        $productImageSetToProductImage = SpyProductImageSetToProductImageQuery::create()->findOneByFkProductImageSet($this->productImageSetTransfer->getIdProductImageSet());

        $eventTransfers = [];

        if ($productImageSetToProductImage) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($productImageSetToProductImage->getIdProductImageSetToProductImage());
        }

        // Act
        $productConcreteImageSetProductImageStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE);

        // Assert
        $this->assertProductConcreteImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteImageSetProductImageStoragePublishListener(): void
    {
        // Prepare
        SpyProductConcreteImageStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->delete();
        $beforeCount = SpyProductConcreteImageStorageQuery::create()->count();

        $productConcreteImageSetProductImageStoragePublishListener = new ProductConcreteImageSetProductImageStoragePublishListener();
        $productConcreteImageSetProductImageStoragePublishListener->setFacade($this->getProductImageStorageFacade());

        $productImageSetToProductImage = SpyProductImageSetToProductImageQuery::create()->findOneByFkProductImageSet($this->productImageSetTransfer->getIdProductImageSet());

        $eventTransfers = [];

        if ($productImageSetToProductImage) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($productImageSetToProductImage->getIdProductImageSetToProductImage());
        }

        // Act
        $productConcreteImageSetProductImageStoragePublishListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE);

        // Assert
        $this->assertProductConcreteImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteImageSetProductImageStorageUnpublishListener(): void
    {
        // Prepare
        $productConcreteImageSetProductImageStorageUnpublishListener = new ProductConcreteImageSetProductImageStorageUnpublishListener();
        $productConcreteImageSetProductImageStorageUnpublishListener->setFacade($this->getProductImageStorageFacade());

        $productImageSetToProductImage = SpyProductImageSetToProductImageQuery::create()->findOneByFkProductImageSet($this->productImageSetTransfer->getIdProductImageSet());

        $eventTransfers = [];

        if ($productImageSetToProductImage) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($productImageSetToProductImage->getIdProductImageSetToProductImage());
        }

        SpyProductImageSetToProductImageQuery::create()->findOneByFkProductImageSet(
            $this->productImageSetTransfer->getIdProductImageSet()
        )->delete();

        // Act
        $productConcreteImageSetProductImageStorageUnpublishListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_DELETE);

        // Assert
        $this->assertSame(0, SpyProductConcreteImageStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->count());
    }

    /**
     * @return void
     */
    public function testProductAbstractImageSetStoragePublishListenerSortsBySortOrderAsc(): void
    {
        // Prepare
        $this->createProducts();

        $productImageTransferSortedSecond = $this->tester->createProductImageTransferWithSortOrder(1);
        $productImageTransferSortedThird = $this->tester->createProductImageTransferWithSortOrder(2);
        $productImageTransferSortedFirst = $this->tester->createProductImageTransferWithSortOrder(0);

        $this->productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ProductImageSetTransfer::PRODUCT_IMAGES => [
                $productImageTransferSortedThird,
                $productImageTransferSortedFirst,
                $productImageTransferSortedSecond,
            ],
        ]);

        SpyProductAbstractImageStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();

        $productAbstractImageSetStoragePublishListener = new ProductAbstractImageSetStoragePublishListener();
        $productAbstractImageSetStoragePublishListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];

        // Act
        $productAbstractImageSetStoragePublishListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE);
        $productImages = $this->getProductAbstractImages();

        // Assert
        $this->assertEquals($productImageTransferSortedFirst->getIdProductImage(), $productImages[0]['id_product_image']);
        $this->assertEquals($productImageTransferSortedSecond->getIdProductImage(), $productImages[1]['id_product_image']);
        $this->assertEquals($productImageTransferSortedThird->getIdProductImage(), $productImages[2]['id_product_image']);
    }

    /**
     * @return void
     */
    public function testProductAbstractImageSetStoragePublishListenerSortsByIdProductImageSetToProductImageAsc(): void
    {
        // Prepare
        $this->createProducts();

        $this->productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ProductImageSetTransfer::PRODUCT_IMAGES => [
                $this->tester->createProductImageTransferWithSortOrder(0),
                $this->tester->createProductImageTransferWithSortOrder(0),
                $this->tester->createProductImageTransferWithSortOrder(0),
            ],
        ]);

        SpyProductAbstractImageStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();

        $productAbstractImageSetStoragePublishListener = new ProductAbstractImageSetStoragePublishListener();
        $productAbstractImageSetStoragePublishListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];

        // Act
        $productAbstractImageSetStoragePublishListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE);
        $productImages = $this->getProductAbstractImages();

        // Assign
        $this->assertSortingByIdProductImageSetToProductImage($productImages);
    }

    /**
     * @return void
     */
    public function testProductConcreteImageSetStoragePublishListenerSortsBySortOrderAsc(): void
    {
        // Prepare
        $this->createProducts();

        $productImageTransferSortedSecond = $this->tester->createProductImageTransferWithSortOrder(1);
        $productImageTransferSortedThird = $this->tester->createProductImageTransferWithSortOrder(2);
        $productImageTransferSortedFirst = $this->tester->createProductImageTransferWithSortOrder(0);

        $this->productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $this->productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::PRODUCT_IMAGES => [
                $productImageTransferSortedThird,
                $productImageTransferSortedFirst,
                $productImageTransferSortedSecond,
            ],
        ]);

        SpyProductConcreteImageStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->delete();

        $productConcreteImageSetStoragePublishListener = new ProductConcreteImageSetStoragePublishListener();
        $productConcreteImageSetStoragePublishListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_PRODUCT => $this->productConcreteTransfer->getIdProductConcrete(),
            ]),
        ];

        // Act
        $productConcreteImageSetStoragePublishListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE);
        $productImages = $this->getProductConcreteImages();

        // Assert
        $this->assertEquals($productImageTransferSortedFirst->getIdProductImage(), $productImages[0]['id_product_image']);
        $this->assertEquals($productImageTransferSortedSecond->getIdProductImage(), $productImages[1]['id_product_image']);
        $this->assertEquals($productImageTransferSortedThird->getIdProductImage(), $productImages[2]['id_product_image']);
    }

    /**
     * @return void
     */
    public function testProductConcreteImageSetStoragePublishListenerSortsByIdProductImageSetToProductImageAsc(): void
    {
        // Prepare
        $this->createProducts();

        $this->productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $this->productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::PRODUCT_IMAGES => [
                $this->tester->createProductImageTransferWithSortOrder(0),
                $this->tester->createProductImageTransferWithSortOrder(0),
                $this->tester->createProductImageTransferWithSortOrder(0),
            ],
        ]);

        SpyProductConcreteImageStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->delete();

        $productConcreteImageSetStoragePublishListener = new ProductConcreteImageSetStoragePublishListener();
        $productConcreteImageSetStoragePublishListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_PRODUCT => $this->productConcreteTransfer->getIdProductConcrete(),
            ]),
        ];

        // Act
        $productConcreteImageSetStoragePublishListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE);
        $productImages = $this->getProductConcreteImages();

        // Assert
        $this->assertSortingByIdProductImageSetToProductImage($productImages);
    }

    /**
     * @return array
     */
    protected function getProductAbstractImages(): array
    {
        $productImageStorage = SpyProductAbstractImageStorageQuery::create()->findOneByFkProductAbstract(
            $this->productAbstractTransfer->getIdProductAbstract()
        );
        $productImages = $productImageStorage->getData()['image_sets'][0]['images'];

        return $productImages;
    }

    /**
     * @return array
     */
    protected function getProductConcreteImages(): array
    {
        $productImageStorage = SpyProductConcreteImageStorageQuery::create()->findOneByFkProduct(
            $this->productConcreteTransfer->getIdProductConcrete()
        );
        $productImages = $productImageStorage->getData()['image_sets'][0]['images'];

        return $productImages;
    }

    /**
     * @return \Spryker\Zed\ProductImageStorage\Business\ProductImageStorageFacade
     */
    protected function getProductImageStorageFacade()
    {
        $factory = new ProductImageStorageBusinessFactory();
        $factory->setConfig(new ProductImageStorageConfigMock());

        $facade = new ProductImageStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductAbstractImageStorage($beforeCount): void
    {
        $productImageStorageCount = SpyProductAbstractImageStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $productImageStorageCount);
        $spyProductAbstractImageStorage = SpyProductAbstractImageStorageQuery::create()->orderByIdProductAbstractImageStorage()->findOneByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract());
        $this->assertNotNull($spyProductAbstractImageStorage);
        $data = $spyProductAbstractImageStorage->getData();
        $this->assertSame(ProductImageDataHelper::NAME, $data['image_sets'][0]['name']);
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductConcreteImageStorage(int $beforeCount): void
    {
        $productImageStorageCount = SpyProductConcreteImageStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $productImageStorageCount);
        $productConcreteImageStorage = SpyProductConcreteImageStorageQuery::create()->orderByIdProductConcreteImageStorage()->findOneByFkProduct($this->productConcreteTransfer->getIdProductConcrete());
        $this->assertNotNull($productConcreteImageStorage);
        $data = $productConcreteImageStorage->getData();
        $this->assertSame(ProductImageDataHelper::NAME, $data['image_sets'][0]['name']);
    }

    /**
     * @param array $productImages
     *
     * @return void
     */
    protected function assertSortingByIdProductImageSetToProductImage(array $productImages): void
    {
        $idProductImageSetToProductImagePrevious = 0;
        foreach ($productImages as $productImage) {
            $idProductImageSetToProductImage = SpyProductImageSetToProductImageQuery::create()
                ->findOneByFkProductImage($productImage['id_product_image'])
                ->getIdProductImageSetToProductImage();
            $this->assertTrue(
                $idProductImageSetToProductImage > $idProductImageSetToProductImagePrevious
            );
            $idProductImageSetToProductImagePrevious = $idProductImageSetToProductImage;
        }
    }
}
