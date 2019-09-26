<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundle\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
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
            ->findConfigurableBundleTemplate(
                (new ConfigurableBundleTemplateFilterTransfer())
                    ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            );

        $this->assertNull($removedConfigurableBundleTemplateTransfer);
    }

    /**
     * @return void
     */
    public function testFindConfigurableBundleTemplateWillReturnNullIfTemplateNotFound(): void
    {
        // Arrange
        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->setIdConfigurableBundleTemplate(-1);

        // Act
        $configurableBundleTemplateTransfer = $this->tester->getFacade()
            ->findConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);

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
    public function testFindConfigurableBundleTemplateWillReturnTransfer(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        // Act
        $foundConfigurableBundleTemplateTransfer = $this->tester
            ->getFacade()
            ->findConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);

        // Assert
        $this->assertNotNull($foundConfigurableBundleTemplateTransfer);
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
        $configurableBundleResponseTransfer = $this->tester
            ->getFacade()
            ->updateConfigurableBundleTemplate($updatedConfigurableBundleTemplateTransfer);

        // Assert
        $this->assertTrue($configurableBundleResponseTransfer->getIsSuccessful());
        $updatedConfigurableBundleTemplateTransfer = $configurableBundleResponseTransfer->getConfigurableBundleTemplate();
        $this->assertNotNull($updatedConfigurableBundleTemplateTransfer);
        $this->assertNotSame($configurableBundleTemplateTransfer->getName(), $updatedConfigurableBundleTemplateTransfer->getName());
    }

    /**
     * @return void
     */
    public function testUpdateConfigurableBundleTemplateWillReturnNotSuccessfulResponseIfTemplateNotFound(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateTransfer->setIdConfigurableBundleTemplate(0);

        // Act
        $configurableBundleResponseTransfer = $this->tester
            ->getFacade()
            ->updateConfigurableBundleTemplate($configurableBundleTemplateTransfer);

        // Assert
        $this->assertFalse($configurableBundleResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateConfigurableBundleTemplateWillCreateTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createConfigurableBundleTemplateTransfer();

        // Act
        $configurableBundleResponseTransfer = $this->tester
            ->getFacade()
            ->createConfigurableBundleTemplate($configurableBundleTemplateTransfer);

        // Assert
        $this->assertTrue($configurableBundleResponseTransfer->getIsSuccessful());
        $configurableBundleTemplateTransfer = $configurableBundleResponseTransfer->getConfigurableBundleTemplate();
        $this->assertNotNull($configurableBundleTemplateTransfer);
        $this->assertGreaterThan(0, $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());
    }

    /**
     * @return void
     */
    public function testActivateConfigurableBundleTemplateByIdActivatesTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createDeactivatedConfigurableBundleTemplate();

        // Act
        $this->tester
            ->getFacade()
            ->activateConfigurableBundleTemplateById($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        // Assert
        $updatedConfigurableBundleTemplateTransfer = $this->tester
            ->getFacade()
            ->findConfigurableBundleTemplate(
                (new ConfigurableBundleTemplateFilterTransfer())
                    ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            );

        $this->assertSame($updatedConfigurableBundleTemplateTransfer->getIsActive(), true);
    }

    /**
     * @return void
     */
    public function testDeactivateConfigurableBundleTemplateByIdDeactivatesTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();

        // Act
        $this->tester
            ->getFacade()
            ->deactivateConfigurableBundleTemplateById($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        // Assert
        $updatedConfigurableBundleTemplateTransfer = $this->tester
            ->getFacade()
            ->findConfigurableBundleTemplate(
                (new ConfigurableBundleTemplateFilterTransfer())
                    ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            );

        $this->assertSame($updatedConfigurableBundleTemplateTransfer->getIsActive(), false);
    }
}
