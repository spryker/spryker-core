<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundle\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ConfigurableBundle
 * @group Business
 * @group Facade
 * @group ConfigurableBundleFacadeTest
 * Add your own group annotations below this line
 */
class ConfigurableBundleFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ConfigurableBundle\ConfigurableBundleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDeleteConfigurableBundleTemplateByIdDeletesTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();

        // Act
        $this->tester->getFacade()
            ->deleteConfigurableBundleTemplateById($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        // Assert
        $removedConfigurableBundleTemplateTransfer = $this->tester->getFacade()
            ->findConfigurableBundleTemplateById($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        $this->assertNull($removedConfigurableBundleTemplateTransfer);
    }

    /**
     * @return void
     */
    protected function filterInactiveItemsFiltersDeletedItems(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransferWithConfigurableBundleTemplate('not-existing-template-uuid');

        // Act
        $filteredQuoteTransfer = $this->tester->getFacade()
            ->filterInactiveItems($quoteTransfer);

        // Assert
        $this->assertEmpty($filteredQuoteTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testFilterInactiveItemsFiltersDeactivatedItems(): void
    {
        // Arrange
        $configurableBundleTemplate = $this->tester->createDeactivatedConfigurableBundleTemplate();
        $quoteTransfer = $this->tester->createQuoteTransferWithConfigurableBundleTemplate($configurableBundleTemplate->getUuid());

        // Act
        $filteredQuoteTransfer = $this->tester->getFacade()
            ->filterInactiveItems($quoteTransfer);

        // Assert
        $this->assertEmpty($filteredQuoteTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testFilterInactiveItemsDoesNotFilterActiveItems(): void
    {
        // Arrange
        $configurableBundleTemplate = $this->tester->createActiveConfigurableBundleTemplate();
        $quoteTransfer = $this->tester->createQuoteTransferWithConfigurableBundleTemplate($configurableBundleTemplate->getUuid());

        // Act
        $filteredQuoteTransfer = $this->tester->getFacade()
            ->filterInactiveItems($quoteTransfer);

        // Assert
        $this->assertNotEmpty($filteredQuoteTransfer->getItems());
    }
}
