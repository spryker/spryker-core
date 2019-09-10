<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundle\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;

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
    public function testFindConfigurableBundleTemplateByIdWillReturnNull(): void
    {
        // Act
        $configurableBundleTemplateTransfer = $this->tester->getFacade()->findConfigurableBundleTemplateById(0);

        // Assert
        $this->assertNull($configurableBundleTemplateTransfer);
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

    /**
     * @return void
     */
    public function testFindConfigurableBundleTemplateByIdWillReturnTransfer(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();

        // Act
        $foundConfigurableBundleTemplateTransfer = $this->tester
            ->getFacade()
            ->findConfigurableBundleTemplateById($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        // Assert
        $this->assertInstanceOf(ConfigurableBundleTemplateTransfer::class, $foundConfigurableBundleTemplateTransfer);
        $this->assertSame(
            $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
            $foundConfigurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()
        );
        $this->assertSame(
            $configurableBundleTemplateTransfer->getName(),
            $foundConfigurableBundleTemplateTransfer->getName()
        );
    }

    /**
     * @return void
     */
    public function testUpdateConfigurableBundleTemplateWillReturnSuccessfulResponse(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $updatedConfigurableBundleTemplateTransfer = clone $configurableBundleTemplateTransfer;
        $updatedConfigurableBundleTemplateTransfer->setTranslations(
            $this->tester->createTranslationTransfersForAvailableLocales([
                ConfigurableBundleTemplateTranslationTransfer::NAME => 'brand new name',
            ])
        );

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->updateConfigurableBundleTemplate($updatedConfigurableBundleTemplateTransfer);

        // Assert
        $this->assertInstanceOf(ConfigurableBundleTemplateResponseTransfer::class, $configurableBundleTemplateResponseTransfer);
        $this->assertTrue($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $updatedConfigurableBundleTemplateTransfer = $configurableBundleTemplateResponseTransfer->getConfigurableBundleTemplate();
        $this->assertInstanceOf(ConfigurableBundleTemplateTransfer::class, $updatedConfigurableBundleTemplateTransfer);
        $this->assertNotSame($configurableBundleTemplateTransfer->getName(), $updatedConfigurableBundleTemplateTransfer->getName());
    }

    /**
     * @return void
     */
    public function testUpdateConfigurableBundleTemplateWillReturnNotSuccessfulResponse(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateTransfer->setIdConfigurableBundleTemplate(0);

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->updateConfigurableBundleTemplate($configurableBundleTemplateTransfer);

        // Assert
        $this->assertInstanceOf(ConfigurableBundleTemplateResponseTransfer::class, $configurableBundleTemplateResponseTransfer);
        $this->assertFalse($configurableBundleTemplateResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateConfigurableBundleTemplate(): void
    {
        $configurableBundleTemplateTransfer = $this->createConfigurableBundleTemplateTransfer();

        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->createConfigurableBundleTemplate($configurableBundleTemplateTransfer);

        $this->assertInstanceOf(ConfigurableBundleTemplateResponseTransfer::class, $configurableBundleTemplateResponseTransfer);
        $this->assertTrue($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $configurableBundleTemplateTransfer = $configurableBundleTemplateResponseTransfer->getConfigurableBundleTemplate();
        $this->assertInstanceOf(ConfigurableBundleTemplateTransfer::class, $configurableBundleTemplateTransfer);
        $this->assertGreaterThan(0, $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());
    }

    /**
     * @return void
     */
    public function testActivateConfigurableBundleTemplateActivatesTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createDeactivatedConfigurableBundleTemplate();

        // Act
        $this->tester
            ->getFacade()
            ->activateConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        // Assert
        $updatedConfigurableBundleTemplateTransfer = $this->tester
            ->getFacade()
            ->findConfigurableBundleTemplateById($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        $this->assertSame($updatedConfigurableBundleTemplateTransfer->getIsActive(), true);
    }

    /**
     * @return void
     */
    public function testDeactivateConfigurableBundleTemplateDeactivatesTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();

        // Act
        $this->tester
            ->getFacade()
            ->deactivateConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        // Assert
        $updatedConfigurableBundleTemplateTransfer = $this->tester
            ->getFacade()
            ->findConfigurableBundleTemplateById($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        $this->assertSame($updatedConfigurableBundleTemplateTransfer->getIsActive(), false);
    }

    /**
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    protected function createConfigurableBundleTemplateTransfer(): ConfigurableBundleTemplateTransfer
    {
        $configurableBundleTemplateTranslationTransfers = $this->tester->createTranslationTransfersForAvailableLocales();

        return (new ConfigurableBundleTemplateTransfer())->setTranslations($configurableBundleTemplateTranslationTransfers);
    }
}
