<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractStoreTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorageQuery;
use Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorageQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductStorage\Business\ProductStorageBusinessFactory;
use Spryker\Zed\ProductStorage\Business\ProductStorageFacade;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductAbstractLocalizedAttributesStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductAbstractStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductAbstractStoragePublishListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductAbstractStorageUnpublishListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductAbstractStoreStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductAbstractUrlStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteLocalizedAttributesStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteProductAbstractLocalizedAttributesStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteProductAbstractRelationStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteProductAbstractStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteProductAbstractUrlStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteStoragePublishListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteStorageUnpublishListener;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use Spryker\Zed\Url\Dependency\UrlEvents;
use SprykerTest\Zed\ProductStorage\ProductStorageConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductStorageListenerTest extends Unit
{
    /**
     * @var int
     */
    protected const NUMBER_OF_STORES = 3;

    /**
     * @var int
     */
    protected const NUMBER_OF_LOCALES = 1;

    /**
     * @var \SprykerTest\Zed\ProductStorage\ProductStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $productAbstractTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $this->tester->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->productConcreteTransfer = $this->tester->haveProduct();
        $this->productAbstractTransfer = $this->tester->getProductFacade()->findProductAbstractById(
            $this->productConcreteTransfer->getFkProductAbstract(),
        );

        $localizedAttributes = $this->tester->generateLocalizedAttributes();

        $this->tester->addLocalizedAttributesToProductAbstract($this->productAbstractTransfer, $localizedAttributes);
        $this->addStoreRelationToProductAbstracts($this->productAbstractTransfer);
        $this->tester->addLocalizedAttributesToProductConcrete($this->productConcreteTransfer, $localizedAttributes);
        $this->tester->cleanUpProcessedAbstractProductIds();
        $this->tester->cleanUpProcessedConcreteProductIds();
    }

    /**
     * @return void
     */
    public function testProductAbstractStorageListenerStoreDataToProcessUniqueProducts(): void
    {
        // Assert
        $productStorageFacadeMock = $this->tester->mockProductStorageFacade();
        $productStorageFacadeMock->expects($this->once())
            ->method('publishAbstractProducts')
            ->with([$this->productAbstractTransfer->getIdProductAbstract(), 1234]);
        $productStorageFacadeMock->expects($this->once())
            ->method('unpublishProductAbstracts')
            ->with([$this->productAbstractTransfer->getIdProductAbstract(), 1234]);

        // Arrange
        $productAbstractStoragePublishListener = new ProductAbstractStoragePublishListener();
        $productAbstractStoragePublishListener->setFacade($productStorageFacadeMock);
        $productAbstractLocalizedAttributesStorageListener = new ProductAbstractLocalizedAttributesStorageListener();
        $productAbstractLocalizedAttributesStorageListener->setFacade($productStorageFacadeMock);
        $productAbstractStorageUnpublishListener = new ProductAbstractStorageUnpublishListener();
        $productAbstractStorageUnpublishListener->setFacade($productStorageFacadeMock);
        $productAbstractStoreStorageListener = new ProductAbstractStoreStorageListener();
        $productAbstractStoreStorageListener->setFacade($productStorageFacadeMock);

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
            (new EventEntityTransfer())->setId(1234),
        ];

        // Act
        $productAbstractStoragePublishListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_PUBLISH);
        $productAbstractLocalizedAttributesStorageListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_PUBLISH);
        $productAbstractStorageUnpublishListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_UNPUBLISH);
        $productAbstractStoreStorageListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_UNPUBLISH);
    }

    /**
     * @return void
     */
    public function testProductConcreteStorageListenerStoreDataToProcessUniqueProducts(): void
    {
        // Assert
        $productStorageFacadeMock = $this->tester->mockProductStorageFacade();
        $productStorageFacadeMock->expects($this->once())
            ->method('publishConcreteProducts')
            ->with([$this->productConcreteTransfer->getIdProductConcrete(), 1234]);
        $productStorageFacadeMock->expects($this->once())
            ->method('unpublishConcreteProducts')
            ->with([$this->productConcreteTransfer->getIdProductConcrete(), 1234]);

        // Arrange
        $productConcreteStoragePublishListener = new ProductConcreteStoragePublishListener();
        $productConcreteStoragePublishListener->setFacade($productStorageFacadeMock);
        $productConcreteProductAbstractStorageListener = new ProductConcreteProductAbstractStorageListener();
        $productConcreteProductAbstractStorageListener->setFacade($productStorageFacadeMock);
        $productConcreteStoragePublishListener = new ProductConcreteStoragePublishListener();
        $productConcreteStoragePublishListener->setFacade($productStorageFacadeMock);

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productConcreteTransfer->getIdProductConcrete()),
            (new EventEntityTransfer())->setId($this->productConcreteTransfer->getIdProductConcrete()),
            (new EventEntityTransfer())->setId(1234),
        ];

        // Act
        $productConcreteStoragePublishListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_CONCRETE_PUBLISH);
        $productConcreteProductAbstractStorageListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_CONCRETE_PUBLISH);
        $productConcreteStoragePublishListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_CONCRETE_UNPUBLISH);
    }

    /**
     * @dataProvider getProductListenerDataProvider
     *
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface $listener
     * @param callable $callableEventEntityTransfer
     * @param string $eventName
     * @param int $deltaTime
     * @param \PHPUnit\Framework\MockObject\Rule\InvokedCount $invokedCountMatcher
     * @param bool $isAbstractProduct
     *
     * @return void
     */
    public function testProductStorageListenerEventsWithTimestamps(
        EventBulkHandlerInterface $listener,
        callable $callableEventEntityTransfer,
        string $eventName,
        int $deltaTime,
        InvokedCountMatcher $invokedCountMatcher,
        bool $isAbstractProduct
    ): void {
        // Arrange
        $lastStorageTimestamp =
            $isAbstractProduct ?
                $this->tester->getAbstractProductStorageEntityTimestamp($this->productConcreteTransfer->getFkProductAbstract()) :
                $this->tester->getProductConcreteStorageEntityTimestamp($this->productConcreteTransfer->getIdProductConcrete());
        if ($lastStorageTimestamp === null) {
            $productStoragePublishListener = $isAbstractProduct ? new ProductAbstractStoragePublishListener() : new ProductConcreteStoragePublishListener();
            $productStoragePublishListener->handleBulk([(new EventEntityTransfer())->setId($isAbstractProduct ? $this->productConcreteTransfer->getFkProductAbstract() : $this->productConcreteTransfer->getIdProductConcrete())], $eventName);
            $this->tester->cleanUpProcessedConcreteProductIds();
            $this->tester->cleanUpProcessedAbstractProductIds();
        }

        // Assert
        $productStorageFacadeMock = $this->tester->mockProductStorageFacade();
        $productStorageFacadeMock->expects($invokedCountMatcher)->method($isAbstractProduct ? 'publishAbstractProducts' : 'publishConcreteProducts');

        // Arrange
        $lastStorageTimestamp =
            $isAbstractProduct ?
                $this->tester->getAbstractProductStorageEntityTimestamp($this->productConcreteTransfer->getFkProductAbstract()) :
                $this->tester->getProductConcreteStorageEntityTimestamp($this->productConcreteTransfer->getIdProductConcrete());
        $listener->setFacade($productStorageFacadeMock);
        $eventEntityTransfer = $callableEventEntityTransfer($this->productConcreteTransfer);
        $eventEntityTransfer->setTimestamp($lastStorageTimestamp + $deltaTime);

        // Act
        $listener->handleBulk([$eventEntityTransfer], $eventName);
        $this->tester->cleanUpProcessedConcreteProductIds();
        $this->tester->cleanUpProcessedAbstractProductIds();
    }

    /**
     * @return void
     */
    public function testProductAbstractStorageListenerStoreData(): void
    {
        // Arrange
        SpyProductAbstractStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractStorageQuery::create()->count();

        $productAbstractStorageListener = new ProductAbstractStorageListener();
        $productAbstractStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];

        // Act
        $productAbstractStorageListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_PUBLISH);

        // Assert
        $this->assertProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductAbstractStoragePublishListener(): void
    {
        // Arrange
        SpyProductAbstractStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractStorageQuery::create()->count();

        $productAbstractStoragePublishListener = new ProductAbstractStoragePublishListener();
        $productAbstractStoragePublishListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];

        // Act
        $productAbstractStoragePublishListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_PUBLISH);

        // Assert
        $this->assertProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductAbstractStorageUnpublishListener(): void
    {
        // Arrange
        $productAbstractStorageUnpublishListener = new ProductAbstractStorageUnpublishListener();
        $productAbstractStorageUnpublishListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];

        // Act
        $productAbstractStorageUnpublishListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_UNPUBLISH);

        // Assert
        $this->assertSame(0, SpyProductAbstractStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->count());
    }

    /**
     * @return void
     */
    public function testProductAbstractUrlStorageListenerStoreData(): void
    {
        // Arrange
        SpyProductAbstractStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractStorageQuery::create()->count();

        $productAbstractUrlStorageListener = new ProductAbstractUrlStorageListener();
        $productAbstractUrlStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ])
            ->setModifiedColumns([SpyUrlTableMap::COL_URL]),
        ];

        // Act
        $productAbstractUrlStorageListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_CREATE);

        // Assert
        $this->assertProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductAbstractLocalizedAttributesStorageListenerStoreData(): void
    {
        // Arrange
        SpyProductAbstractStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractStorageQuery::create()->count();

        $productAbstractLocalizedAttributesStorageListener = new ProductAbstractLocalizedAttributesStorageListener();
        $productAbstractLocalizedAttributesStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];

        // Act
        $productAbstractLocalizedAttributesStorageListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_UPDATE);

        // Assert
        $this->assertProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteProductAbstractRelationStorageListenerStoreData(): void
    {
        // Arrange
        SpyProductAbstractStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractStorageQuery::create()->count();

        $productConcreteProductAbstractRelationStorageListener = new ProductConcreteProductAbstractRelationStorageListener();
        $productConcreteProductAbstractRelationStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];

        // Act
        $productConcreteProductAbstractRelationStorageListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_CREATE);

        // Assert
        $this->assertProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteStorageListenerStoreData(): void
    {
        // Arrange
        SpyProductConcreteStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->delete();
        $beforeCount = SpyProductConcreteStorageQuery::create()->count();

        $productConcreteStorageListener = new ProductConcreteStorageListener();
        $productConcreteStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productConcreteTransfer->getIdProductConcrete()),
        ];

        // Act
        $productConcreteStorageListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_CONCRETE_PUBLISH);

        // Assert
        $this->assertProductConcreteStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteStoragePublishListener(): void
    {
        // Arrange
        SpyProductConcreteStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->delete();
        $beforeCount = SpyProductConcreteStorageQuery::create()->count();

        $productConcreteStoragePublishListener = new ProductConcreteStoragePublishListener();
        $productConcreteStoragePublishListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productConcreteTransfer->getIdProductConcrete()),
        ];

        // Act
        $productConcreteStoragePublishListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_CONCRETE_PUBLISH);

        // Assert
        $this->assertProductConcreteStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteStorageUnpublishListener(): void
    {
        // Arrange
        $productConcreteStorageUnpublishListener = new ProductConcreteStorageUnpublishListener();
        $productConcreteStorageUnpublishListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productConcreteTransfer->getIdProductConcrete()),
        ];

        // Act
        $productConcreteStorageUnpublishListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_CONCRETE_UNPUBLISH);

        // Assert
        $this->assertSame(0, SpyProductConcreteStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->count());
    }

    /**
     * @return void
     */
    public function testProductConcreteRelationUrlStorageListenerStoreData(): void
    {
        // Arrange
        SpyProductConcreteStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->delete();
        $beforeCount = SpyProductConcreteStorageQuery::create()->count();

        $productConcreteProductAbstractUrlStorageListener = new ProductConcreteProductAbstractUrlStorageListener();
        $productConcreteProductAbstractUrlStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ])
                ->setModifiedColumns([SpyUrlTableMap::COL_URL]),
        ];

        // Act
        $productConcreteProductAbstractUrlStorageListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_CREATE);

        // Assert
        $this->assertProductConcreteStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteProductAbstractStorageListenerStoreData(): void
    {
        // Arrange
        SpyProductConcreteStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->delete();
        $beforeCount = SpyProductConcreteStorageQuery::create()->count();

        $productConcreteProductAbstractStorageListener = new ProductConcreteProductAbstractStorageListener();
        $productConcreteProductAbstractStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];

        // Act
        $productConcreteProductAbstractStorageListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_UPDATE);

        // Assert
        $this->assertProductConcreteStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteProductAbstractLocalizedAttributesStorageListenerStoreData(): void
    {
        // Arrange
        SpyProductConcreteStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->delete();
        $beforeCount = SpyProductConcreteStorageQuery::create()->count();

        $productConcreteProductAbstractLocalizedAttributesStorageListener = new ProductConcreteProductAbstractLocalizedAttributesStorageListener();
        $productConcreteProductAbstractLocalizedAttributesStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];

        // Act
        $productConcreteProductAbstractLocalizedAttributesStorageListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_UPDATE);

        // Assert
        $this->assertProductConcreteStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteLocalizedAttributesStorageListenerStoreData(): void
    {
        // Arrange
        SpyProductConcreteStorageQuery::create()->filterByFkProduct($this->productConcreteTransfer->getIdProductConcrete())->delete();
        $beforeCount = SpyProductConcreteStorageQuery::create()->count();

        $productConcreteLocalizedAttributesStorageListener = new ProductConcreteLocalizedAttributesStorageListener();
        $productConcreteLocalizedAttributesStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT => $this->productConcreteTransfer->getIdProductConcrete(),
            ]),
        ];

        // Act
        $productConcreteLocalizedAttributesStorageListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_UPDATE);

        // Assert
        $this->assertProductConcreteStorage($beforeCount);
    }

    /**
     * @return \Spryker\Zed\ProductStorage\Business\ProductStorageFacade
     */
    protected function getProductStorageFacade(): ProductStorageFacade
    {
        $factory = new ProductStorageBusinessFactory();
        $factory->setConfig(new ProductStorageConfigMock());

        $facade = new ProductStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductAbstractStorage(int $beforeCount): void
    {
        $afterCount = SpyProductAbstractStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $spyProductAbstractStorage = SpyProductAbstractStorageQuery::create()
            ->orderByIdProductAbstractStorage()
            ->findOneByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract());

        $urlCollectionEntity = SpyUrlQuery::create()
            ->orderByFkResourceProductAbstract()
            ->findByFkResourceProductAbstract($this->productAbstractTransfer->getIdProductAbstract());

        $this->assertNotNull($urlCollectionEntity);
        $this->assertNotNull($urlCollectionEntity->count());
        $this->assertNotNull($spyProductAbstractStorage);
        $data = $spyProductAbstractStorage->getData();
        $this->assertSame($this->productAbstractTransfer->getSku(), $data['sku']);
        $this->assertSame(1, count($data['attributes']));
        $this->assertContains($data['url'], $urlCollectionEntity->getColumnValues('url'));
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductConcreteStorage(int $beforeCount): void
    {
        $afterCount = SpyProductConcreteStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $spyProductConcreteStorage = SpyProductConcreteStorageQuery::create()
            ->orderByIdProductConcreteStorage()
            ->findOneByFkProduct($this->productConcreteTransfer->getIdProductConcrete());

        $urlCollectionEntity = SpyUrlQuery::create()
            ->orderByFkResourceProductAbstract()
            ->findByFkResourceProductAbstract($this->productAbstractTransfer->getIdProductAbstract());

        $this->assertNotNull($urlCollectionEntity);
        $this->assertNotNull($urlCollectionEntity->count());

        $this->assertNotNull($spyProductConcreteStorage);
        $data = $spyProductConcreteStorage->getData();

        $this->assertSame($this->productConcreteTransfer->getSku(), $data['sku']);
        $this->assertSame(1, count($data['attributes']));
        $this->assertContains($data['url'], $urlCollectionEntity->getColumnValues('url'));
    }

    /**
     * @return array
     */
    protected function getIdStores(): array
    {
        $storeIds = [];

        foreach ($this->getStoreFacade()->getAllStores() as $storeTransfer) {
            $storeIds[] = $storeTransfer->getIdStore();
        }

        return $storeIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function addStoreRelationToProductAbstracts(ProductAbstractTransfer $productAbstractTransfer): void
    {
        $idStores = $this->getIdStores();

        $productAbstractTransfer->setStoreRelation((new StoreRelationTransfer())->setIdStores($idStores));

        $this->tester->getProductFacade()->saveProductAbstract($productAbstractTransfer);
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->tester->getLocator()->store()->facade();
    }

    /**
     * @return array
     */
    public function getProductListenerDataProvider(): array
    {
        return [
             // concrete product - events when earlier or equal timestamp
            'ProductConcreteProductAbstractStorageListener with earlier or equal timestamp' => [
                new ProductConcreteProductAbstractStorageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())->setId($productConcreteTransfer->getFkProductAbstract());
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                0,
                $this->never(),
                false,
            ],
            'ProductConcreteLocalizedAttributesStorageListener with earlier or equal timestamp' => [
                new ProductConcreteLocalizedAttributesStorageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId($productConcreteTransfer->getFkProductAbstract())
                        ->setForeignKeys([SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete()]);
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                0,
                $this->never(),
                false,
            ],
            'ProductConcreteProductAbstractUrlStorageListener with earlier or equal timestamp' => [
                new ProductConcreteProductAbstractUrlStorageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setModifiedColumns([SpyUrlTableMap::COL_URL, SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT])
                        ->setForeignKeys([SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                0,
                $this->never(),
                false,
            ],
            'ProductConcreteStoragePublishListener with earlier or equal timestamp' => [
                new ProductConcreteStoragePublishListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())->setId($productConcreteTransfer->getIdProductConcrete());
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                0,
                $this->never(),
                false,
            ],
            'ProductConcreteProductAbstractRelationStorageListener with earlier or equal timestamp' => [
                new ProductConcreteProductAbstractRelationStorageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                0,
                $this->never(),
                false,
            ],
            'ProductConcreteProductAbstractLocalizedAttributesStorageListener with earlier or equal timestamp' => [
                new ProductConcreteProductAbstractLocalizedAttributesStorageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                0,
                $this->never(),
                false,
            ],
            // concrete product - new events with a later timestamp
            'ProductConcreteProductAbstractStorageListener with a later timestamp' => [
                new ProductConcreteProductAbstractStorageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())->setId($productConcreteTransfer->getFkProductAbstract());
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                1,
                $this->once(),
                false,
            ],
            'ProductConcreteLocalizedAttributesStorageListener with a later timestamp' => [
                new ProductConcreteLocalizedAttributesStorageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId($productConcreteTransfer->getFkProductAbstract())
                        ->setForeignKeys([SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete()]);
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                1,
                $this->once(),
                false,
            ],
            'ProductConcreteProductAbstractUrlStorageListener with a later timestamp' => [
                new ProductConcreteProductAbstractUrlStorageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setModifiedColumns([SpyUrlTableMap::COL_URL, SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT])
                        ->setForeignKeys([SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                1,
                $this->once(),
                false,
            ],
            'ProductConcreteStoragePublishListener with a later timestamp' => [
                new ProductConcreteStoragePublishListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())->setId($productConcreteTransfer->getIdProductConcrete());
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                1,
                $this->once(),
                false,
            ],
            'ProductConcreteProductAbstractRelationStorageListener with a later timestamp' => [
                new ProductConcreteProductAbstractRelationStorageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                1,
                $this->once(),
                false,
            ],
            'ProductConcreteProductAbstractLocalizedAttributesStorageListener with a later timestamp' => [
                new ProductConcreteProductAbstractLocalizedAttributesStorageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                1,
                $this->once(),
                false,
            ],
            // abstract product - events when earlier or equal timestamp
            'ProductAbstractStoragePublishListener with earlier or equal timestamp' => [
                new ProductAbstractStoragePublishListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())->setId($productConcreteTransfer->getFkProductAbstract());
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            'ProductAbstractLocalizedAttributesStorageListener with earlier or equal timestamp' => [
                new ProductAbstractLocalizedAttributesStorageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getIdProductConcrete()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            'ProductAbstractStoreStorageListener with earlier or equal timestamp' => [
                new ProductAbstractStoreStorageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductAbstractStoreTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            'ProductAbstractUrlStorageListener with earlier or equal timestamp' => [
                new ProductAbstractUrlStorageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()])
                        ->setModifiedColumns([SpyUrlTableMap::COL_URL, SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            // abstract product - new events with a later timestamp
            'ProductAbstractStoragePublishListener with a later timestamp' => [
                new ProductAbstractStoragePublishListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())->setId($productConcreteTransfer->getFkProductAbstract());
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            'ProductAbstractLocalizedAttributesStorageListener with a later timestamp' => [
                new ProductAbstractLocalizedAttributesStorageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getIdProductConcrete()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            'ProductAbstractStoreStorageListener with a later timestamp' => [
                new ProductAbstractStoreStorageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductAbstractStoreTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            'ProductAbstractUrlStorageListener with a later timestamp' => [
                new ProductAbstractUrlStorageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()])
                        ->setModifiedColumns([SpyUrlTableMap::COL_URL, SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
        ];
    }
}
