<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorageQuery;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorageQuery;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface;
use Spryker\Zed\ProductLabel\Dependency\ProductLabelEvents;
use Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageBusinessFactory;
use Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacade;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener\ProductLabelDictionaryStorageListener;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener\ProductLabelDictionaryStoragePublishListener;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener\ProductLabelDictionaryStorageUnpublishListener;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener\ProductLabelPublishStorageListener;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener\ProductLabelStorageListener;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\ProductLabelStorage\ProductLabelStorageConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductLabelStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductLabelStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductLabelStorageListenerTest extends Unit
{
    use LocatorHelperTrait;

    protected const STORE_NAME_DE = 'DE';
    protected const STORE_NAME_AT = 'AT';
    protected const LOCALE_NAME_EN = 'en_US';

    /**
     * @var \SprykerTest\Zed\ProductLabelStorage\ProductLabelStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected $productLabelTransfer;

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
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->productAbstractTransfer = $this->tester->haveProductAbstract();
        $this->productLabelTransfer = $this->tester->haveProductLabel();

        $localizedAttributes = $this->tester->generateLocalizedAttributes(
            $this->getLocaleFacade()->getCurrentLocale()->getIdLocale()
        );
        $this->tester->addLocalizedAttributesToProductAbstract($this->productAbstractTransfer, $localizedAttributes);

        $this->tester->haveProductLabelToAbstractProductRelation(
            $this->productLabelTransfer->getIdProductLabel(),
            $this->productAbstractTransfer->getIdProductAbstract()
        );
    }

    /**
     * @return void
     */
    public function testProductLabelPublishStorageListenerStoreData(): void
    {
        // Arrange
        $this->tester->deleteProductAbstractLabelStorageByIdProductAbstract($this->productAbstractTransfer->getIdProductAbstract());
        $beforeCount = $this->tester->getProductAbstractLabelStorageCount();

        $productLabelPublishStorageListener = new ProductLabelPublishStorageListener();
        $productLabelPublishStorageListener->setFacade($this->getProductLabelStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];

        // Act
        $productLabelPublishStorageListener->handleBulk($eventTransfers, ProductLabelEvents::PRODUCT_LABEL_PRODUCT_ABSTRACT_PUBLISH);

        // Assert
        $this->assertProductAbstractLabelStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductLabelStorageListenerStoreData(): void
    {
        // Arrange
        $this->tester->deleteProductAbstractLabelStorageByIdProductAbstract($this->productAbstractTransfer->getIdProductAbstract());
        $beforeCount = $this->tester->getProductAbstractLabelStorageCount();

        $productLabelStorageListener = new ProductLabelStorageListener();
        $productLabelStorageListener->setFacade($this->getProductLabelStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];

        // Act
        $productLabelStorageListener->handleBulk($eventTransfers, ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_CREATE);

        // Assert
        $this->assertProductAbstractLabelStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductLabelDictionaryStorageListenerStoreData(): void
    {
        // Arrange
        $productLabelDictionaryStorageListener = new ProductLabelDictionaryStorageListener();
        $productLabelDictionaryStorageListener->setFacade($this->getProductLabelStorageFacade());

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeRelationSeedData = [
            StoreRelationTransfer::ID_STORES => [
                $storeTransfer->getIdStore(),
            ],
            StoreRelationTransfer::STORES => [
                $storeTransfer,
            ],
        ];

        $this->tester->haveProductLabel([
            ProductLabelTransfer::STORE_RELATION => $storeRelationSeedData,
        ]);

        // Act
        $productLabelDictionaryStorageListener->handleBulk([], ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_CREATE);

        // Assert
        $localeName = $this->getLocaleFacade()->getCurrentLocale()->getLocaleName();
        $this->getProductLabelFacade()->checkLabelValidityDateRangeAndTouch();
        $spyProductLabelDictionaryStorage = SpyProductLabelDictionaryStorageQuery::create()
            ->filterByStore($storeTransfer->getName())
            ->filterByLocale($localeName)
            ->findOne();
        $this->assertNotNull($spyProductLabelDictionaryStorage);
        $data = $spyProductLabelDictionaryStorage->getData();
        $labelsCount = $this->tester->getProductLabelsCountByStoreNameAndLocaleName(
            $storeTransfer->getName(),
            $localeName
        );
        $this->assertCount($labelsCount, $data['items'], 'Number of items does not equals to an expected value.');
    }

    /**
     * @return void
     */
    public function testProductLabelDictionaryStoragePublishListener(): void
    {
        // Arrange
        $this->tester->clearProductAbstractLabelStorage();
        $productLabelDictionaryStoragePublishListener = new ProductLabelDictionaryStoragePublishListener();
        $productLabelDictionaryStoragePublishListener->setFacade($this->getProductLabelStorageFacade());

        //Arrange
        $storeTransferDE = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeTransferAT = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $storeRelationSeedData = [
            StoreRelationTransfer::ID_STORES => [
                $storeTransferDE->getIdStore(),
                $storeTransferAT->getIdStore(),
            ],
            StoreRelationTransfer::STORES => [
                $storeTransferDE,
                $storeTransferAT,

            ],
        ];

        $this->tester->haveProductLabel([
            ProductLabelTransfer::STORE_RELATION => $storeRelationSeedData,
        ]);

        // Act
        $productLabelDictionaryStoragePublishListener->handleBulk([], ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_CREATE);

        // Assert
        $localeName = $this->getLocaleFacade()->getCurrentLocale()->getLocaleName();
        $this->getProductLabelFacade()->checkLabelValidityDateRangeAndTouch();
        $spyProductLabelDictionaryStorage = SpyProductLabelDictionaryStorageQuery::create()
            ->filterByStore($storeTransferDE->getName())
            ->filterByLocale($localeName)
            ->findOne();
        $this->assertNotNull($spyProductLabelDictionaryStorage);
        $data = $spyProductLabelDictionaryStorage->getData();
        $labelsCount = $this->tester->getProductLabelsCountByStoreNameAndLocaleName(
            $storeTransferDE->getName(),
            $localeName
        );
        $this->assertCount($labelsCount, $data['items'], 'Number of items does not equals to an expected value.');
    }

    /**
     * @return void
     */
    public function testProductLabelDictionaryStorageUnpublishListener(): void
    {
        // Arrange
        $productLabelDictionaryStorageUnpublishListener = new ProductLabelDictionaryStorageUnpublishListener();
        $productLabelDictionaryStorageUnpublishListener->setFacade($this->getProductLabelStorageFacade());

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_NAME_EN]);

        $this->tester->haveProductLabelDictionaryStorage([
            ProductLabelDictionaryStorageTransfer::STORE => $storeTransfer->getName(),
            ProductLabelDictionaryStorageTransfer::LOCALE => $localeTransfer->getLocaleName(),
        ]);

        // Act
        $productLabelDictionaryStorageUnpublishListener->handleBulk([], ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_DELETE);

        // Assert
        $productLabelDictionaryStorageEntityCount = SpyProductLabelDictionaryStorageQuery::create()->count();

        $this->assertEquals(
            0,
            $productLabelDictionaryStorageEntityCount,
            'Product label dictionary storage entities number does not equals to an expected number'
        );
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductAbstractLabelStorage(int $beforeCount): void
    {
        $productLabelStorageCount = $this->tester->getProductAbstractLabelStorageCount();
        $this->assertGreaterThan($beforeCount, $productLabelStorageCount);
        $spyProductAbstractLabelStorage = SpyProductAbstractLabelStorageQuery::create()
            ->orderByIdProductAbstractLabelStorage()
            ->findOneByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract());
        $this->assertNotNull($spyProductAbstractLabelStorage);
        $data = $spyProductAbstractLabelStorage->getData();
        $this->assertCount(1, $data['product_label_ids']);
    }

    /**
     * @return \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacade
     */
    protected function getProductLabelStorageFacade(): ProductLabelStorageFacade
    {
        $factory = new ProductLabelStorageBusinessFactory();
        $factory->setConfig(new ProductLabelStorageConfigMock());

        $facade = new ProductLabelStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    protected function getProductLabelFacade(): ProductLabelFacadeInterface
    {
        return $this->tester->getLocator()->productLabel()->facade();
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->tester->getLocator()->locale()->facade();
    }
}
