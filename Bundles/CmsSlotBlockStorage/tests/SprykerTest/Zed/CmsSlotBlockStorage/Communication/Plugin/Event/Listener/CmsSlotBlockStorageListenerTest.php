<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsSlotBlockStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\SpyCmsSlotToCmsSlotTemplateEntityTransfer;
use Orm\Zed\CmsSlotBlockStorage\Persistence\SpyCmsSlotBlockStorageQuery;
use Spryker\Zed\CmsSlotBlock\Dependency\CmsSlotBlockEvents;
use Spryker\Zed\CmsSlotBlockStorage\Business\CmsSlotBlockStorageBusinessFactory;
use Spryker\Zed\CmsSlotBlockStorage\Communication\Plugin\Event\Listener\CmsSlotBlockStoragePublishListener;
use Spryker\Zed\CmsSlotBlockStorage\Persistence\CmsSlotBlockStorageEntityManager;
use Spryker\Zed\CmsSlotBlockStorage\Persistence\CmsSlotBlockStoragePersistenceFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsSlotBlockStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group CmsSlotBlockStorageListenerTest
 * Add your own group annotations below this line
 */
class CmsSlotBlockStorageListenerTest extends Unit
{
    protected const COUNT_CMS_SLOT_BLOCK_STORAGE_ROWS = 1;

    /**
     * @var \SprykerTest\Zed\CmsSlotBlockStorage\CmsSlotBlockStorageCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCmsSlotBlockStoragePublishListener(): void
    {
        // Assign
        $this->tester->ensureCmsSlotBlockTableIsEmpty();
        $this->tester->ensureCmsSlotBlockStorageTableIsEmpty();

        $cmsSlotTransfer = $this->tester->haveCmsSlotInDb();
        $cmsSlotTemplateTransfer = $this->tester->haveCmsSlotTemplateInDb();
        $cmsBlockTransfer = $this->tester->haveCmsBlock();

        $cmsSlotToCmsSlotTemplateEntityTransfer = (new SpyCmsSlotToCmsSlotTemplateEntityTransfer())
            ->setFkCmsSlotTemplate($cmsSlotTemplateTransfer->getIdCmsSlotTemplate())
            ->setFkCmsSlot($cmsSlotTransfer->getIdCmsSlot());
        $this->tester->haveCmsSlotToCmsSlotTemplateInDb($cmsSlotToCmsSlotTemplateEntityTransfer);

        $cmsSlotBlockTransfer = $this->tester->haveCmsSlotBlockInDb([
            CmsSlotBlockTransfer::ID_SLOT_TEMPLATE => $cmsSlotTemplateTransfer->getIdCmsSlotTemplate(),
            CmsSlotBlockTransfer::ID_SLOT => $cmsSlotTransfer->getIdCmsSlot(),
            CmsSlotBlockTransfer::ID_CMS_BLOCK => $cmsBlockTransfer->getIdCmsBlock(),
        ]);

        $eventTransfers = [
            (new EventEntityTransfer())->setId($cmsSlotBlockTransfer->getIdCmsSlotBlock()),
        ];
        $cmsSlotBlockStoragePublishListenerMock = new CmsSlotBlockStoragePublishListener();
        $cmsSlotBlockStoragePublishListenerMock->setFacade($this->getCmsSlotBlockStorageFacade());

        // Act
        $cmsSlotBlockStoragePublishListenerMock->handleBulk(
            $eventTransfers,
            CmsSlotBlockEvents::CMS_SLOT_BLOCK_PUBLISH
        );
        $cmsSlotBlockStorageEntity = SpyCmsSlotBlockStorageQuery::create()
            ->filterByFkCmsSlotTemplate($cmsSlotBlockTransfer->getIdSlotTemplate())
            ->filterByFkCmsSlot($cmsSlotBlockTransfer->getIdSlot())
            ->findOne();

        // Assert
        $count = SpyCmsSlotBlockStorageQuery::create()->count();
        $this->assertSame(static::COUNT_CMS_SLOT_BLOCK_STORAGE_ROWS, $count);
        $this->assertNotNull($cmsSlotBlockStorageEntity);
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockStorage\Business\CmsSlotBlockStorageFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getCmsSlotBlockStorageFacade()
    {
        $this->tester->mockConfigMethod('isSendingToQueue', false);

        $cmsSlotBlockStoragePersistenceFactoryMock = new CmsSlotBlockStoragePersistenceFactory();
        $cmsSlotBlockStoragePersistenceFactoryMock->setConfig($this->tester->getModuleConfig());

        $cmsSlotBlockStorageEntityManagerMock = $this
            ->getMockBuilder(CmsSlotBlockStorageEntityManager::class)
            ->onlyMethods(['getFactory'])
            ->getMock();
        $cmsSlotBlockStorageEntityManagerMock->method('getFactory')
            ->willReturn($cmsSlotBlockStoragePersistenceFactoryMock);

        $cmsSlotBlockStorageBusinessFactoryMock = new CmsSlotBlockStorageBusinessFactory();
        $cmsSlotBlockStorageBusinessFactoryMock->setEntityManager($cmsSlotBlockStorageEntityManagerMock);

        $cmsSlotBlockStorageFacade = $this->tester->getFacade();
        $cmsSlotBlockStorageFacade->setFactory($cmsSlotBlockStorageBusinessFactoryMock);

        return $cmsSlotBlockStorageFacade;
    }
}
