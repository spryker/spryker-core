<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorageQuery;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorageQuery;
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

    public const STORE_NAME_DE = 'DE';
    public const STORE_NAME_AT = 'AT';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

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
        $this->tester->clearProductAbstractLabelStorage();
        $productLabelDictionaryStorageListener = new ProductLabelDictionaryStorageListener();
        $productLabelDictionaryStorageListener->setFacade($this->getProductLabelStorageFacade());

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

        //Act
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::STORE_RELATION => $storeRelationSeedData,
        ]);

        $eventTransfers = [
            (new EventEntityTransfer())->setId($productLabelTransfer->getIdProductLabel()),
        ];

        // Act
        $productLabelDictionaryStorageListener->handleBulk($eventTransfers, ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_CREATE);

        // Assert
        $localeName = $this->getLocaleFacade()->getCurrentLocale()->getLocaleName();
        $this->getProductLabelFacade()->checkLabelValidityDateRangeAndTouch();
        $spyProductLabelDictionaryStorage = SpyProductLabelDictionaryStorageQuery::create()
            ->filterByStore($storeTransferDE->getName())
            ->filterByLocale($localeName)
            ->findOne();
        $this->assertNotNull($spyProductLabelDictionaryStorage);
        $data = $spyProductLabelDictionaryStorage->getData();
        $labelsCount = $this->tester->getProductLabelsCountByIdProductLabelAndStoreNameAndLocaleName(
            $productLabelTransfer->getIdProductLabel(),
            $storeTransferDE->getName(),
            $localeName
        );
        $this->assertSame($labelsCount, count($data['items']));
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

        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::STORE_RELATION => $storeRelationSeedData,
        ]);

        $eventTransfers = [
            (new EventEntityTransfer())->setId($productLabelTransfer->getIdProductLabel()),
        ];

        // Act
        $productLabelDictionaryStoragePublishListener->handleBulk($eventTransfers, ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_CREATE);

        // Assert
        $localeName = $this->getLocaleFacade()->getCurrentLocale()->getLocaleName();
        $this->getProductLabelFacade()->checkLabelValidityDateRangeAndTouch();
        $spyProductLabelDictionaryStorage = SpyProductLabelDictionaryStorageQuery::create()
            ->filterByStore($storeTransferDE->getName())
            ->filterByLocale($localeName)
            ->findOne();
        $this->assertNotNull($spyProductLabelDictionaryStorage);
        $data = $spyProductLabelDictionaryStorage->getData();
        $labelsCount = $this->tester->getProductLabelsCountByIdProductLabelAndStoreNameAndLocaleName(
            $productLabelTransfer->getIdProductLabel(),
            $storeTransferDE->getName(),
            $localeName
        );
        $this->assertSame($labelsCount, count($data['items']));
    }

    /**
     * @return void
     */
    public function testProductLabelDictionaryStorageUnpublishListener(): void
    {
        // Arrange
        $productLabelDictionaryStorageUnpublishListener = new ProductLabelDictionaryStorageUnpublishListener();
        $productLabelDictionaryStorageUnpublishListener->setFacade($this->getProductLabelStorageFacade());

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

        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::STORE_RELATION => $storeRelationSeedData,
        ]);

        $eventTransfers = [
            (new EventEntityTransfer())->setId($productLabelTransfer->getIdProductLabel()),
        ];

        // Act
        $productLabelDictionaryStorageUnpublishListener->handleBulk($eventTransfers, ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_DELETE);

        // Assert
        $localeName = $this->getLocaleFacade()->getCurrentLocale()->getLocaleName();
        $this->getProductLabelFacade()->checkLabelValidityDateRangeAndTouch();
        $spyProductLabelDictionaryStorage = SpyProductLabelDictionaryStorageQuery::create()
            ->filterByStore($storeTransferDE->getName())
            ->filterByLocale($localeName)
            ->findOne();

        foreach ($spyProductLabelDictionaryStorage->getData()['items'] as $productLabelDictionaryItem) {
            $this->assertNotEquals($productLabelTransfer->getIdProductLabel(), $productLabelDictionaryItem['id_product_label'], 'Product label dictionary storage item should be deleted');
        }
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
        $spyProductAbstractLabelStorage = SpyProductAbstractLabelStorageQuery::create()->orderByIdProductAbstractLabelStorage()->findOneByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract());
        $this->assertNotNull($spyProductAbstractLabelStorage);
        $data = $spyProductAbstractLabelStorage->getData();
        $this->assertSame(1, count($data['product_label_ids']));
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
