<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundlePageSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchFilterTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\ConfigurableBundle\Dependency\ConfigurableBundleEvents;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ConfigurableBundlePageSearch
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ConfigurableBundlePageSearchListenerTest
 * Add your own group annotations below this line
 */
class ConfigurableBundlePageSearchListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->tester->setDependencies();
    }

    /**
     * @return void
     */
    public function testConfigurableBundleTemplatePublishListenerStoresData(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createConfigurableBundleTemplate();
        $configurableBundleTemplateIds = [$configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()];
        $configurableBundleTemplatePublishListener = $this->tester->createConfigurableBundleTemplatePublishListener();
        $eventTransfers = [
            (new EventEntityTransfer())->setId($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()),
        ];

        // Act
        $configurableBundleTemplatePublishListener->handleBulk($eventTransfers, ConfigurableBundleEvents::CONFIGURABLE_BUNDLE_TEMPLATE_PUBLISH);
        $configurableBundleTemplatePageSearchCollectionTransfer = $this->tester->getFacade()->getConfigurableBundleTemplatePageSearchCollection(
            (new ConfigurableBundleTemplatePageSearchFilterTransfer())->setConfigurableBundleTemplateIds($configurableBundleTemplateIds)
        );

        // Assert
        $this->assertNotEmpty($configurableBundleTemplatePageSearchCollectionTransfer->getConfigurableBundleTemplatePageSearches());
    }

    /**
     * @return void
     */
    public function testConfigurableBundleTemplateUnpublishListenerStoresData(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createConfigurableBundleTemplate();
        $configurableBundleTemplateIds = [$configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()];
        $configurableBundleTemplateUnpublishListener = $this->tester->createConfigurableBundleTemplateUnpublishListener();
        $eventTransfers = [
            (new EventEntityTransfer())->setId($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()),
        ];

        // Act
        $this->tester->getFacade()->publishConfigurableBundleTemplates($configurableBundleTemplateIds);
        $configurableBundleTemplateUnpublishListener->handleBulk($eventTransfers, ConfigurableBundleEvents::CONFIGURABLE_BUNDLE_TEMPLATE_PUBLISH);
        $configurableBundleTemplatePageSearchCollectionTransfer = $this->tester->getFacade()->getConfigurableBundleTemplatePageSearchCollection(
            (new ConfigurableBundleTemplatePageSearchFilterTransfer())->setConfigurableBundleTemplateIds($configurableBundleTemplateIds)
        );

        // Assert
        $this->assertEmpty($configurableBundleTemplatePageSearchCollectionTransfer->getConfigurableBundleTemplatePageSearches());
    }
}
