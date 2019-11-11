<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsSlotBlockStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Spryker\Zed\CmsSlotBlockStorage\Business\CmsSlotBlockStorageBusinessFactory;
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
    /**
     * @var \SprykerTest\Zed\CmsSlotBlockStorage\CmsSlotBlockStorageCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCmsSlotBlockStoragePublishListener(): void
    {
        // TODO: add test
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
            ->setMethods(['getFactory'])
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
