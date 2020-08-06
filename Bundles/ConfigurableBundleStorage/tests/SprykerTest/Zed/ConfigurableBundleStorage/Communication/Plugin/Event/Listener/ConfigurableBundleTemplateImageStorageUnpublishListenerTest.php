<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\ConfigurableBundle\Dependency\ConfigurableBundleEvents;
use Spryker\Zed\ConfigurableBundleStorage\Communication\Plugin\Event\Listener\ConfigurableBundleTemplateImageStoragePublishListener;
use Spryker\Zed\ConfigurableBundleStorage\Communication\Plugin\Event\Listener\ConfigurableBundleTemplateImageStorageUnpublishListener;
use Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageRepository;

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
 * @group ConfigurableBundleTemplateImageStorageUnpublishListenerTest
 * Add your own group annotations below this line
 */
class ConfigurableBundleTemplateImageStorageUnpublishListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ConfigurableBundleStorage\ConfigurableBundleStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageRepositoryInterface
     */
    protected $configurableBundleStorageRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->configurableBundleStorageRepository = new ConfigurableBundleStorageRepository();
        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    /**
     * @return void
     */
    public function testConfigurableBundleTemplateImageStorageUnpublishListenerCanBeUnpublished(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();

        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::FK_RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
            ProductImageSetTransfer::LOCALE => null,
        ]);

        $configurableBundleTemplateImageStoragePublishListener = (new ConfigurableBundleTemplateImageStoragePublishListener())
            ->setFacade($this->tester->getFacade());

        $configurableBundleTemplateImageStorageUnpublishListener = (new ConfigurableBundleTemplateImageStorageUnpublishListener())
            ->setFacade($this->tester->getFacade());

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()),
        ];

        // Act
        $configurableBundleTemplateImageStoragePublishListener->handleBulk($eventEntityTransfers, ConfigurableBundleEvents::CONFIGURABLE_BUNDLE_TEMPLATE_IMAGE_PUBLISH);
        $configurableBundleTemplateImageStorageUnpublishListener->handleBulk($eventEntityTransfers, ConfigurableBundleEvents::CONFIGURABLE_BUNDLE_TEMPLATE_IMAGE_UNPUBLISH);

        $configurableBundleTemplateImageStorageEntities = $this->configurableBundleStorageRepository
            ->getConfigurableBundleTemplateImageStorageEntityMap([$configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()]);

        // Assert
        $this->assertEmpty($configurableBundleTemplateImageStorageEntities);
    }
}
