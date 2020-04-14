<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockGlossaryKeyMappingTableMap;
use Orm\Zed\CmsBlockStorage\Persistence\Map\SpyCmsBlockStorageTableMap;
use Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorageQuery;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\CmsBlock\Dependency\CmsBlockEvents;
use Spryker\Zed\CmsBlockStorage\Business\CmsBlockStorageBusinessFactory;
use Spryker\Zed\CmsBlockStorage\Business\CmsBlockStorageFacade;
use Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener\CmsBlockGlossaryKeyMappingBlockStoragePublishListener;
use Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener\CmsBlockGlossaryKeyMappingBlockStorageUnpublishListener;
use Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener\CmsBlockStoragePublishListener;
use Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener\CmsBlockStorageUnpublishListener;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerTest\Zed\CmsBlockStorage\CmsBlockStorageConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsBlockStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group CmsBlockStorageListenerTest
 * Add your own group annotations below this line
 */
class CmsBlockStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CmsBlockStorage\CmsBlockStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var int[]
     */
    protected $storeIds;

    /**
     * @var int[]
     */
    protected $localeIds;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->storeIds = $this->getStoreIds();
        $this->localeIds = $this->getLocaleIds();
    }

    /**
     * @return void
     */
    public function testCmsBlockStorageUnpublishListener(): void
    {
        // Arrange
        $cmsBlockTransfer = $this->tester->createCmsBlock($this->storeIds, $this->localeIds);
        $cmsBlockStorageUnpublishListener = new CmsBlockStorageUnpublishListener();
        $cmsBlockStorageUnpublishListener->setFacade($this->getCmsBlockStorageFacade());
        $eventTransfers = [
            (new EventEntityTransfer())->setId($cmsBlockTransfer->getIdCmsBlock()),
        ];

        // Act
        $cmsBlockStorageUnpublishListener->handleBulk($eventTransfers, CmsBlockEvents::CMS_BLOCK_UNPUBLISH);

        // Assert
        $this->assertFalse(
            SpyCmsBlockStorageQuery::create()
                ->filterByFkCmsBlock($cmsBlockTransfer->getIdCmsBlock())
                ->exists()
        );
    }

    /**
     * @return void
     */
    public function testCmsBlockStoragePublishListener(): void
    {
        // Arrange
        $cmsBlockTransfer = $this->tester->createCmsBlock($this->storeIds, $this->localeIds);
        $beforeCount = SpyCmsBlockStorageQuery::create()->count();

        $cmsBlockStoragePublishListener = new CmsBlockStoragePublishListener();
        $cmsBlockStoragePublishListener->setFacade($this->getCmsBlockStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($cmsBlockTransfer->getIdCmsBlock()),
        ];

        // Act
        $cmsBlockStoragePublishListener->handleBulk($eventTransfers, CmsBlockEvents::CMS_BLOCK_PUBLISH);

        // Assert
        $this->assertCmsBlockStorage($beforeCount, $cmsBlockTransfer);
    }

    /**
     * @return void
     */
    public function testCmsBlockGlossaryKeyMappingBlockStoragePublishListener(): void
    {
        // Arrange
        $cmsBlockTransfer = $this->tester->createCmsBlock($this->storeIds, $this->localeIds);
        $beforeCount = SpyCmsBlockStorageQuery::create()->count();

        $cmsBlockGlossaryKeyMappingBlockStoragePublishListener = new CmsBlockGlossaryKeyMappingBlockStoragePublishListener();
        $cmsBlockGlossaryKeyMappingBlockStoragePublishListener->setFacade($this->getCmsBlockStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCmsBlockGlossaryKeyMappingTableMap::COL_FK_CMS_BLOCK => $cmsBlockTransfer->getIdCmsBlock(),
            ]),
        ];

        // Act
        $cmsBlockGlossaryKeyMappingBlockStoragePublishListener->handleBulk($eventTransfers, CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_CREATE);

        // Assert
        $this->assertCmsBlockStorage($beforeCount, $cmsBlockTransfer);
    }

    /**
     * @return void
     */
    public function testCmsBlockGlossaryKeyMappingBlockStorageUnpublishListener(): void
    {
        // Arrange
        $cmsBlockTransfer = $this->tester->createCmsBlock($this->storeIds, $this->localeIds);

        $cmsBlockGlossaryKeyMappingBlockStoragePublishListener = new CmsBlockGlossaryKeyMappingBlockStoragePublishListener();
        $cmsBlockGlossaryKeyMappingBlockStoragePublishListener->setFacade($this->getCmsBlockStorageFacade());

        $cmsBlockGlossaryKeyMappingBlockStorageUnpublishListener = new CmsBlockGlossaryKeyMappingBlockStorageUnpublishListener();
        $cmsBlockGlossaryKeyMappingBlockStorageUnpublishListener->setFacade($this->getCmsBlockStorageFacade());

        $beforeCount = SpyCmsBlockStorageQuery::create()
            ->filterByFkCmsBlock($cmsBlockTransfer->getIdCmsBlock())
            ->count();

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCmsBlockGlossaryKeyMappingTableMap::COL_FK_CMS_BLOCK => $cmsBlockTransfer->getIdCmsBlock(),
            ]),
        ];

        // Act
        $cmsBlockGlossaryKeyMappingBlockStoragePublishListener->handleBulk($eventTransfers, CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_CREATE);
        $cmsBlockGlossaryKeyMappingBlockStorageUnpublishListener->handleBulk($eventTransfers, CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_DELETE);

        $afterCount = SpyCmsBlockStorageQuery::create()
            ->filterByFkCmsBlock($cmsBlockTransfer->getIdCmsBlock())
            ->count();

        // Assert
        $this->assertEquals($beforeCount, $afterCount);
    }

    /**
     * @return \Spryker\Zed\CmsBlockStorage\Business\CmsBlockStorageFacade
     */
    protected function getCmsBlockStorageFacade(): CmsBlockStorageFacade
    {
        $factory = new CmsBlockStorageBusinessFactory();
        $factory->setConfig(new CmsBlockStorageConfigMock());

        $facade = new CmsBlockStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    public function getStoreFacade(): StoreFacadeInterface
    {
        return $this->tester->getLocator()->store()->facade();
    }

    /**
     * @return int[]
     */
    protected function getStoreIds(): array
    {
        $storeIds = [];

        foreach ($this->getStoreFacade()->getAllStores() as $storeTransfer) {
            $storeIds[] = $storeTransfer->getIdStore();
        }

        return $storeIds;
    }

    /**
     * @return int[]
     */
    protected function getLocaleIds(): array
    {
        $localeIds = [];

        foreach ($this->getStoreFacade()->getAllStores() as $storeTransfer) {
            $localeNames = $storeTransfer->getAvailableLocaleIsoCodes();

            $localeIdsFromDb = SpyLocaleQuery::create()
                ->filterByLocaleName_In($localeNames)
                ->select([SpyLocaleTableMap::COL_ID_LOCALE])
                ->find()
                ->toArray();

            $localeIds = array_merge($localeIdsFromDb, $localeIds);
        }

        return array_unique($localeIds);
    }

    /**
     * @param int $beforeCount
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    protected function assertCmsBlockStorage(int $beforeCount, CmsBlockTransfer $cmsBlockTransfer): void
    {
        $count = SpyCmsBlockStorageQuery::create()->count();
        $relatedStoreNames = SpyCmsBlockStorageQuery::create()
            ->filterByFkCmsBlock($cmsBlockTransfer->getIdCmsBlock())
            ->select(SpyCmsBlockStorageTableMap::COL_STORE)
            ->distinct()
            ->find()
            ->toArray();

        $storeTransfers = $this->getStoreFacade()->getAllStores();
        $countLocalesByStores = 0;

        foreach ($storeTransfers as $storeTransfer) {
            if (in_array($storeTransfer->getName(), $relatedStoreNames, true)) {
                $countLocalesByStores += count($storeTransfer->getAvailableLocaleIsoCodes());
            }
        }

        $cmsBlockStorage = SpyCmsBlockStorageQuery::create()
            ->orderByIdCmsBlockStorage()
            ->findOneByFkCmsBlock($cmsBlockTransfer->getIdCmsBlock());
        $this->assertNotNull($cmsBlockStorage);

        $data = $cmsBlockStorage->getData();
        $this->assertSame($beforeCount + $countLocalesByStores, $count);
        $this->assertSame($cmsBlockTransfer->getName(), $data['name']);
    }
}
