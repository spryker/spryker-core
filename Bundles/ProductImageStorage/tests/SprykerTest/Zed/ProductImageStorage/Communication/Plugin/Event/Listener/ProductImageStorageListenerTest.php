<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImageStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorageQuery;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorageQuery;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;
use Spryker\Zed\ProductImageStorage\Business\ProductImageStorageBusinessFactory;
use Spryker\Zed\ProductImageStorage\Business\ProductImageStorageFacade;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageSetProductImageStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageSetStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductConcreteImageSetProductImageStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductConcreteImageSetStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductConcreteImageStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductImageAbstractPublishStorageListener;
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

        $dbEngine = Config::get(PropelQueryBuilderConstants::ZED_DB_ENGINE);
        if ($dbEngine !== 'pgsql') {
            throw new SkippedTestError('Warning: no PostgreSQL is detected');
        }

        $this->productAbstractTransfer = $this->tester->haveProductAbstract();
        $this->productConcreteTransfer = $this->tester->haveProduct();

        $localizedAttributes = $this->tester->generateLocalizedAttributes();

        $this->tester->addLocalizedAttributesToProductAbstract($this->productAbstractTransfer, $localizedAttributes);
        $this->tester->addLocalizedAttributesToProductConcrete($this->productConcreteTransfer, $localizedAttributes);

        $this->productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ProductImageSetTransfer::ID_PRODUCT => $this->productConcreteTransfer->getIdProductConcrete(),
        ]);
    }

    /**
     * @return void
     */
    public function testProductImageAbstractPublishStorageListenerStoreData()
    {
        SpyProductAbstractImageStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractImageStorageQuery::create()->count();

        $productImageAbstractPublishStorageListener = new ProductImageAbstractPublishStorageListener();
        $productImageAbstractPublishStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];
        $productImageAbstractPublishStorageListener->handleBulk($eventTransfers, ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_PUBLISH);

        // Assert
        $this->assertProductAbstractImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductAbstractImageStorageListenerStoreData()
    {
        SpyProductAbstractImageStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractImageStorageQuery::create()->count();

        $productImageAbstractPublishStorageListener = new ProductAbstractImageStorageListener();
        $productImageAbstractPublishStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [];

        foreach ($this->productImageSetTransfer->getProductImages() as $productImageTransfer) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($productImageTransfer->getIdProductImage());
        }

        $productImageAbstractPublishStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE);

        // Assert
        $this->assertProductAbstractImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductAbstractImageSetStorageListenerStoreData()
    {
        SpyProductAbstractImageStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractImageStorageQuery::create()->count();

        $productAbstractImageSetStorageListener = new ProductAbstractImageSetStorageListener();
        $productAbstractImageSetStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];
        $productAbstractImageSetStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE);

        // Assert
        $this->assertProductAbstractImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductAbstractImageSetProductImageStorageListenerStoreData()
    {
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

        $productAbstractImageSetProductImageStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE);

        // Assert
        $this->assertProductAbstractImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductImageConcretePublishStorageListenerStoreData()
    {
        SpyProductConcreteImageStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->delete();
        $beforeCount = SpyProductConcreteImageStorageQuery::create()->count();

        $productImageConcretePublishStorageListener = new ProductImageConcretePublishStorageListener();
        $productImageConcretePublishStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productConcreteTransfer->getIdProductConcrete()),
        ];
        $productImageConcretePublishStorageListener->handleBulk($eventTransfers, ProductImageEvents::PRODUCT_IMAGE_PRODUCT_CONCRETE_PUBLISH);

        // Assert
        $this->assertProductConcreteImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteImageStorageListenerStoreData()
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
    public function testProductConcreteImageSetStorageListenerStoreData()
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
    public function testProductConcreteImageSetProductImageStorageListenerStoreData()
    {
        SpyProductConcreteImageStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->delete();
        $beforeCount = SpyProductConcreteImageStorageQuery::create()->count();

        $productConcreteImageSetProductImageStorageListener = new ProductConcreteImageSetProductImageStorageListener();
        $productConcreteImageSetProductImageStorageListener->setFacade($this->getProductImageStorageFacade());

        $productImageSetToProductImage = SpyProductImageSetToProductImageQuery::create()->findOneByFkProductImageSet($this->productImageSetTransfer->getIdProductImageSet());

        $eventTransfers = [];

        if ($productImageSetToProductImage) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($productImageSetToProductImage->getIdProductImageSetToProductImage());
        }

        $productConcreteImageSetProductImageStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE);

        // Assert
        $this->assertProductConcreteImageStorage($beforeCount);
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
    protected function assertProductAbstractImageStorage($beforeCount)
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
    protected function assertProductConcreteImageStorage($beforeCount)
    {
        $productImageStorageCount = SpyProductConcreteImageStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $productImageStorageCount);
        $productConcreteImageStorage = SpyProductConcreteImageStorageQuery::create()->orderByIdProductConcreteImageStorage()->findOneByFkProduct($this->productConcreteTransfer->getIdProductConcrete());
        $this->assertNotNull($productConcreteImageStorage);
        $data = $productConcreteImageStorage->getData();
        $this->assertSame(ProductImageDataHelper::NAME, $data['image_sets'][0]['name']);
    }
}
