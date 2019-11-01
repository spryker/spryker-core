<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundlePageSearch\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchFilterTransfer;
use Generated\Shared\Transfer\FilterTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ConfigurableBundlePageSearch
 * @group Business
 * @group Facade
 * @group ConfigurableBundlePageSearchFacadeTest
 * Add your own group annotations below this line
 */
class ConfigurableBundlePageSearchFacadeTest extends Unit
{
    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->tester->setDependencies();
    }

    /**
     * @var \SprykerTest\Zed\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPublishConfigurableBundleTemplates(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createConfigurableBundleTemplate();
        $configurableBundleTemplateIds = [$configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()];

        // Act
        $this->tester->getFacade()->publishConfigurableBundleTemplates($configurableBundleTemplateIds);
        $configurableBundleTemplatePageSearchCollectionTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplatePageSearchCollection(
                (new ConfigurableBundleTemplatePageSearchFilterTransfer())->setConfigurableBundleTemplateIds($configurableBundleTemplateIds)
            );
        $configurableBundleTemplatePageSearchTransfers = $configurableBundleTemplatePageSearchCollectionTransfer->getConfigurableBundleTemplatePageSearches();

        // Assert
        $this->assertCount($configurableBundleTemplateTransfer->getTranslations()->count(), $configurableBundleTemplatePageSearchTransfers);

        foreach ($configurableBundleTemplatePageSearchTransfers as $configurableBundleTemplatePageSearchTransfer) {
            $this->assertSame(
                $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
                $configurableBundleTemplatePageSearchTransfer->getFkConfigurableBundleTemplate()
            );
        }
    }

    /**
     * @return void
     */
    public function testUnpublishConfigurableBundleTemplates(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createConfigurableBundleTemplate();
        $configurableBundleTemplateIds = [$configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()];

        // Act
        $this->tester->getFacade()->publishConfigurableBundleTemplates($configurableBundleTemplateIds);
        $this->tester->getFacade()->unpublishConfigurableBundleTemplates($configurableBundleTemplateIds);
        $configurableBundleTemplatePageSearchCollectionTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplatePageSearchCollection(
                (new ConfigurableBundleTemplatePageSearchFilterTransfer())->setConfigurableBundleTemplateIds($configurableBundleTemplateIds)
            );

        // Assert
        $this->assertEmpty($configurableBundleTemplatePageSearchCollectionTransfer->getConfigurableBundleTemplatePageSearches());
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateCollectionWillReturnAllTemplates(): void
    {
        // Arrange
        $this->tester->createConfigurableBundleTemplate();

        // Act
        $configurableBundleTemplateCollectionTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplateCollection(new FilterTransfer());

        // Assert
        $this->assertCount($this->tester->getConfigurableBundleTemplatesCount(), $configurableBundleTemplateCollectionTransfer->getConfigurableBundleTemplates());
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateCollectionWillReturnTemplatesByFilter(): void
    {
        // Arrange
        $this->tester->createConfigurableBundleTemplate();

        // Act
        $configurableBundleTemplateCollectionTransfer = $this->tester->getFacade()->getConfigurableBundleTemplateCollection(
            (new FilterTransfer())->setLimit(1)
        );

        // Assert
        $this->assertCount(1, $configurableBundleTemplateCollectionTransfer->getConfigurableBundleTemplates());
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateCollectionReturnsEmptyCollection(): void
    {
        // Arrange
        $configurableBundleTemplateIds = [-1, -2];

        // Act
        $configurableBundleTemplatePageSearchCollectionTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplatePageSearchCollection(
                (new ConfigurableBundleTemplatePageSearchFilterTransfer())->setConfigurableBundleTemplateIds($configurableBundleTemplateIds)
            );

        // Assert
        $this->assertEmpty($configurableBundleTemplatePageSearchCollectionTransfer->getConfigurableBundleTemplatePageSearches());
    }
}
