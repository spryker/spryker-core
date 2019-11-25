<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\Map\SpyConfigurableBundleTemplateSlotTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\ConfigurableBundle\Dependency\ConfigurableBundleEvents;
use Spryker\Zed\ConfigurableBundleStorage\Communication\Plugin\Event\Listener\ConfigurableBundleTemplateImageStoragePublishListener;
use Spryker\Zed\ConfigurableBundleStorage\Communication\Plugin\Event\Listener\ConfigurableBundleTemplateSlotStoragePublishListener;
use Spryker\Zed\ConfigurableBundleStorage\Communication\Plugin\Event\Listener\ConfigurableBundleTemplateStoragePublishListener;

/**
 * Auto-generated group annotations
 *
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
    public function testConfigurableBundleTemplateStoragePublishListenerStoresDataForActiveTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();

        $configurableBundleTemplateStoragePublishListener = new ConfigurableBundleTemplateStoragePublishListener();
        $configurableBundleTemplateStoragePublishListener->setFacade($this->tester->getFacade());

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()),
        ];

        // Act
        $configurableBundleTemplateStoragePublishListener->handleBulk($eventEntityTransfers, ConfigurableBundleEvents::ENTITY_SPY_CONFIGURABLE_BUNDLE_TEMPLATE_CREATE);

        // Assert
        $this->assertNotNull(
            $this->tester->findConfigurableBundleTemplateStorageById($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
        );
    }

    /**
     * @return void
     */
    public function testConfigurableBundleTemplateStoragePublishListenerStoresDataForDeactivatedTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createDeactivatedConfigurableBundleTemplate();

        $configurableBundleTemplateStoragePublishListener = new ConfigurableBundleTemplateStoragePublishListener();
        $configurableBundleTemplateStoragePublishListener->setFacade($this->tester->getFacade());

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()),
        ];

        // Act
        $configurableBundleTemplateStoragePublishListener->handleBulk($eventEntityTransfers, ConfigurableBundleEvents::ENTITY_SPY_CONFIGURABLE_BUNDLE_TEMPLATE_CREATE);

        // Assert
        $this->assertNotNull(
            $this->tester->findConfigurableBundleTemplateStorageById($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
        );
    }

    /**
     * @return void
     */
    public function testConfigurableBundleTemplateSlotStoragePublishListenerStoresData(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();

        $configurableBundleTemplateStoragePublishListener = new ConfigurableBundleTemplateSlotStoragePublishListener();
        $configurableBundleTemplateStoragePublishListener->setFacade($this->tester->getFacade());

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyConfigurableBundleTemplateSlotTableMap::COL_FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
            ]),
        ];

        // Act
        $configurableBundleTemplateStoragePublishListener->handleBulk($eventEntityTransfers, ConfigurableBundleEvents::ENTITY_SPY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_CREATE);

        // Assert
        $this->assertNotNull(
            $this->tester->findConfigurableBundleTemplateStorageById($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
        );
    }

    /**
     * @return void
     */
    public function testConfigurableBundleTemplateImageStoragePublishListenerStoresDataForActiveTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();

        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::FK_RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        $configurableBundleTemplateImageStoragePublishListener = new ConfigurableBundleTemplateImageStoragePublishListener();
        $configurableBundleTemplateImageStoragePublishListener->setFacade($this->tester->getFacade());

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()),
        ];

        // Act
        $configurableBundleTemplateImageStoragePublishListener->handleBulk($eventEntityTransfers, ConfigurableBundleEvents::CONFIGURABLE_BUNDLE_TEMPLATE_IMAGE_PUBLISH);

        // Assert
        $this->assertNotNull(
            $this->tester->findConfigurableBundleTemplateImageStorageById($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
        );
    }

    /**
     * @return void
     */
    public function testConfigurableBundleTemplateImageStoragePublishListenerStoresDataForDeactivatedTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createDeactivatedConfigurableBundleTemplate();

        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::FK_RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        $configurableBundleTemplateImageStoragePublishListener = new ConfigurableBundleTemplateImageStoragePublishListener();
        $configurableBundleTemplateImageStoragePublishListener->setFacade($this->tester->getFacade());

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()),
        ];

        // Act
        $configurableBundleTemplateImageStoragePublishListener->handleBulk($eventEntityTransfers, ConfigurableBundleEvents::CONFIGURABLE_BUNDLE_TEMPLATE_IMAGE_PUBLISH);

        // Assert
        $this->assertNull(
            $this->tester->findConfigurableBundleTemplateImageStorageById($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
        );
    }

    /**
     * @return void
     */
    public function testConfigurableBundleTemplateImageStoragePublishListenerStoresDataForTemplateWithoutImageSets(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();

        $configurableBundleTemplateImageStoragePublishListener = new ConfigurableBundleTemplateImageStoragePublishListener();
        $configurableBundleTemplateImageStoragePublishListener->setFacade($this->tester->getFacade());

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()),
        ];

        // Act
        $configurableBundleTemplateImageStoragePublishListener->handleBulk($eventEntityTransfers, ConfigurableBundleEvents::CONFIGURABLE_BUNDLE_TEMPLATE_IMAGE_PUBLISH);

        // Assert
        $this->assertNull(
            $this->tester->findConfigurableBundleTemplateImageStorageById($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
        );
    }
}
