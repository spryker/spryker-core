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
 * @group ConfigurableBundleTemplateConfigurableBundlePageSearchPublishListenerTest
 * Add your own group annotations below this line
 */
class ConfigurableBundleTemplateConfigurableBundlePageSearchPublishListenerTest extends Unit
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
    public function testConfigurableBundleTemplatePublishListenerPublishesData(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createConfigurableBundleTemplate();
        $configurableBundleTemplateIds = [$configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()];
        $configurableBundleTemplatePublishListener = $this->tester->createConfigurableBundleTemplatePublishListener();
        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()),
        ];

        // Act
        $configurableBundleTemplatePublishListener->handleBulk($eventEntityTransfers, ConfigurableBundleEvents::CONFIGURABLE_BUNDLE_TEMPLATE_PUBLISH);
        $configurableBundleTemplatePageSearchCollectionTransfer = $this->tester->getFacade()->getConfigurableBundleTemplatePageSearchCollection(
            (new ConfigurableBundleTemplatePageSearchFilterTransfer())->setConfigurableBundleTemplateIds($configurableBundleTemplateIds)
        );

        // Assert
        $this->assertNotEmpty($configurableBundleTemplatePageSearchCollectionTransfer->getConfigurableBundleTemplatePageSearches());
    }
}
