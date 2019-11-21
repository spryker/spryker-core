<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\DataBuilder\ProductLabelLocalizedAttributesBuilder;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorageQuery;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorageQuery;
use Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface;
use Spryker\Zed\ProductLabel\Dependency\ProductLabelEvents;
use Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageBusinessFactory;
use Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacade;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener\ProductLabelDictionaryStorageListener;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener\ProductLabelDictionaryStoragePublishListener;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener\ProductLabelDictionaryStorageUnpublishListener;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener\ProductLabelPublishStorageListener;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener\ProductLabelStorageListener;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
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

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->productAbstractTransfer = $this->tester->haveProductAbstract();
        $this->productLabelTransfer = $this->tester->haveProductLabel();

        $localizedAttributes = $this->tester->generateLocalizedAttributes(
            $this->getLocator()->locale()->facade()->getCurrentLocale()->getIdLocale()
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
        // Prepare
        SpyProductAbstractLabelStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractLabelStorageQuery::create()->count();

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
        // Prepare
        SpyProductAbstractLabelStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractLabelStorageQuery::create()->count();

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
        // Prepare
        SpyProductLabelDictionaryStorageQuery::create()->deleteAll();
        $productLabelDictionaryStorageListener = new ProductLabelDictionaryStorageListener();
        $productLabelDictionaryStorageListener->setFacade($this->getProductLabelStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer()),
        ];

        // Act
        $productLabelDictionaryStorageListener->handleBulk($eventTransfers, ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_CREATE);

        // Assert
        $localeName = $this->getLocator()->locale()->facade()->getCurrentLocale()->getLocaleName();
        $this->getProductLabelFacade()->checkLabelValidityDateRangeAndTouch();
        $spyProductLabelDictionaryStorage = SpyProductLabelDictionaryStorageQuery::create()
            ->filterByLocale($localeName)
            ->findOne();
        $this->assertNotNull($spyProductLabelDictionaryStorage);
        $data = $spyProductLabelDictionaryStorage->getData();
        $labelsCount = $this->getProductLabelsCountByLocaleName($localeName);
        $this->assertSame($labelsCount, count($data['items']));
    }

    /**
     * @return void
     */
    public function testProductLabelDictionaryStoragePublishListener(): void
    {
        // Prepare
        SpyProductLabelDictionaryStorageQuery::create()->deleteAll();
        $productLabelDictionaryStoragePublishListener = new ProductLabelDictionaryStoragePublishListener();
        $productLabelDictionaryStoragePublishListener->setFacade($this->getProductLabelStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer()),
        ];

        // Act
        $productLabelDictionaryStoragePublishListener->handleBulk($eventTransfers, ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_CREATE);

        // Assert
        $localeName = $this->getLocator()->locale()->facade()->getCurrentLocale()->getLocaleName();
        $this->getProductLabelFacade()->checkLabelValidityDateRangeAndTouch();
        $spyProductLabelDictionaryStorage = SpyProductLabelDictionaryStorageQuery::create()
            ->filterByLocale($localeName)
            ->findOne();
        $this->assertNotNull($spyProductLabelDictionaryStorage);
        $data = $spyProductLabelDictionaryStorage->getData();
        $labelsCount = $this->getProductLabelsCountByLocaleName($localeName);
        $this->assertSame($labelsCount, count($data['items']));
    }

    /**
     * @return void
     */
    public function testProductLabelDictionaryStorageUnpublishListener(): void
    {
        // Prepare
        $productLabelDictionaryStorageUnpublishListener = new ProductLabelDictionaryStorageUnpublishListener();
        $productLabelDictionaryStorageUnpublishListener->setFacade($this->getProductLabelStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer()),
        ];

        // Act
        $productLabelDictionaryStorageUnpublishListener->handleBulk($eventTransfers, ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_DELETE);

        // Assert
        $localeName = $this->getLocator()->locale()->facade()->getCurrentLocale()->getLocaleName();
        $this->getProductLabelFacade()->checkLabelValidityDateRangeAndTouch();
        $spyProductLabelDictionaryStorage = SpyProductLabelDictionaryStorageQuery::create()
            ->filterByLocale($localeName)
            ->findOne();
        $this->assertNotNull($spyProductLabelDictionaryStorage);
        $data = $spyProductLabelDictionaryStorage->getData();
        $labelsCount = $this->getProductLabelsCountByLocaleName($localeName);
        $this->assertSame($labelsCount - 1, count($data['items']));
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
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductAbstractLabelGroupStorage(int $beforeCount): void
    {
        $productLabelStorageCount = SpyProductAbstractLabelStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $productLabelStorageCount);
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductAbstractLabelStorage(int $beforeCount): void
    {
        $productLabelStorageCount = SpyProductAbstractLabelStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $productLabelStorageCount);
        $spyProductAbstractLabelStorage = SpyProductAbstractLabelStorageQuery::create()->orderByIdProductAbstractLabelStorage()->findOneByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract());
        $this->assertNotNull($spyProductAbstractLabelStorage);
        $data = $spyProductAbstractLabelStorage->getData();
        $this->assertSame(1, count($data['product_label_ids']));
    }

    /**
     * @param int|null $fkLocale
     * @param int|null $fkProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function generateLocalizedAttributesTransfer(?int $fkLocale = null, ?int $fkProductLabel = null)
    {
        $builder = new ProductLabelLocalizedAttributesBuilder([
            'fkProductLabel' => $fkProductLabel,
            'fkLocale' => $fkLocale,
        ]);

        return $builder->build();
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    protected function getProductLabelFacade(): ProductLabelFacadeInterface
    {
        return $this->tester->getLocator()->productLabel()->facade();
    }

    /**
     * @param string $localeName
     *
     * @return int
     */
    protected function getProductLabelsCountByLocaleName(string $localeName): int
    {
        return SpyProductLabelQuery::create()
            ->filterByValidTo(null)
            ->_or()
            ->filterByValidTo((new DateTime())->format('Y-m-d H:i:s'), Criteria::GREATER_EQUAL)
            ->useSpyProductLabelLocalizedAttributesQuery()
                ->useSpyLocaleQuery()
                    ->filterByLocaleName($localeName)
                ->endUse()
            ->endUse()
            ->count();
    }
}
