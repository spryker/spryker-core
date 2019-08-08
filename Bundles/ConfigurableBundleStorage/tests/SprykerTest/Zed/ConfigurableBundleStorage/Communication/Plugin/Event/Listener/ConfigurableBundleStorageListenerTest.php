<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\Map\SpyConfigurableBundleTemplateSlotTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\ConfigurableBundle\Dependency\ConfigurableBundleEvents;
use Spryker\Zed\ConfigurableBundleStorage\Communication\Plugin\Event\Listener\ConfigurableBundleTemplateSlotStoragePublishListener;
use Spryker\Zed\ConfigurableBundleStorage\Communication\Plugin\Event\Listener\ConfigurableBundleTemplateStoragePublishListener;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ConfigurableBundleStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ConfigurableBundleStorageListenerTest
 * Add your own group annotations below this line
 */
class ConfigurableBundleStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ConfigurableBundleStorage\ConfigurableBundleStorageCommunicationTester
     */
    protected $tester;

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
    }

    /**
     * @return void
     */
    public function testConfigurableBundleTemplateStoragePublishListenerStoresData()
    {
        // Arrange
        $configurableBundleTemplateEntity = $this->tester->createConfigurableBundleTemplate();

        $configurableBundleTemplateStorageListener = new ConfigurableBundleTemplateStoragePublishListener();
        $configurableBundleTemplateStorageListener->setFacade($this->tester->getFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($configurableBundleTemplateEntity->getIdConfigurableBundleTemplate()),
        ];

        // Act
        $configurableBundleTemplateStorageListener->handleBulk($eventTransfers, ConfigurableBundleEvents::ENTITY_SPY_CONFIGURABLE_BUNDLE_TEMPLATE_CREATE);

        // Assert
        $this->assertNotNull(
            $this->tester->findConfigurableBundleTemplateStorageById($configurableBundleTemplateEntity->getIdConfigurableBundleTemplate())
        );
    }

    /**
     * @return void
     */
    public function testConfigurableBundleTemplateSlotStoragePublishListenerStoresData()
    {
        // Arrange
        $configurableBundleTemplateEntity = $this->tester->createConfigurableBundleTemplate();

        $configurableBundleTemplateStorageListener = new ConfigurableBundleTemplateSlotStoragePublishListener();
        $configurableBundleTemplateStorageListener->setFacade($this->tester->getFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyConfigurableBundleTemplateSlotTableMap::COL_FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateEntity->getIdConfigurableBundleTemplate(),
            ]),
        ];

        // Act
        $configurableBundleTemplateStorageListener->handleBulk($eventTransfers, ConfigurableBundleEvents::ENTITY_SPY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_CREATE);

        // Assert
        $this->assertNotNull(
            $this->tester->findConfigurableBundleTemplateStorageById($configurableBundleTemplateEntity->getIdConfigurableBundleTemplate())
        );
    }
}
