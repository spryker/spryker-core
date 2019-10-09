<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundle\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTranslationTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Generated\Shared\Transfer\ProductListTransfer;

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
    public function testUpdateConfigurableBundleTemplateWillReturnSuccessfulResponse(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $updatedConfigurableBundleTemplateTransfer = clone $configurableBundleTemplateTransfer;
        $updatedConfigurableBundleTemplateTransfer->setTranslations(
            $this->tester->createTemplateTranslationTransfersForAvailableLocales([
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

    /**
     * @return void
     */
    public function testCheckProductListUsageAmongSlotsWillReturnNotSuccessfullResponseWhenProductListIsUsed(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        // Act
        $productListResponseTransfer = $this->tester->getFacade()->checkProductListUsageAmongSlots(
            $configurableBundleTemplateSlotTransfer->getProductList()
        );

        // Assert
        $this->assertFalse($productListResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $productListResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckProductListUsageAmongSlotsWillReturnSuccessfullResponseWhenProductListIsNotUsed(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);
        $productListTransfer = (new ProductListTransfer())->setIdProductList(-1);

        // Act
        $productListResponseTransfer = $this->tester->getFacade()->checkProductListUsageAmongSlots($productListTransfer);

        // Assert
        $this->assertTrue($productListResponseTransfer->getIsSuccessful());
        $this->assertCount(0, $productListResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCreateConfigurableBundleTemplateSlotTransfer(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlotTransfer();
        $configurableBundleTemplateSlotTransfer->setFkConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        // Act
        $configurableBundleResponseTransfer = $this->tester
            ->getFacade()
            ->createConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);

        // Assert
        $this->assertTrue($configurableBundleResponseTransfer->getIsSuccessful());
        $configurableBundleTemplateSlotTransfer = $configurableBundleResponseTransfer->getConfigurableBundleTemplateSlot();
        $this->assertNotNull($configurableBundleTemplateSlotTransfer);
        $this->assertGreaterThan(0, $configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot());
    }

    /**
     * @return void
     */
    public function testUpdateConfigurableBundleTemplatSloteWillReturnSuccessfulResponse(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);
        $updatedConfigurableBundleTemplateSlotTransfer = clone $configurableBundleTemplateSlotTransfer;
        $updatedConfigurableBundleTemplateSlotTransfer->setTranslations(
            $this->tester->createSlotTranslationTransfersForAvailableLocales([
                ConfigurableBundleTemplateSlotTranslationTransfer::NAME => 'brand new name',
            ])
        );

        // Act
        $configurableBundleResponseTransfer = $this->tester
            ->getFacade()
            ->updateConfigurableBundleTemplateSlot($updatedConfigurableBundleTemplateSlotTransfer);

        // Assert
        $this->assertTrue($configurableBundleResponseTransfer->getIsSuccessful());
        $updatedConfigurableBundleTemplateSlotTransfer = $configurableBundleResponseTransfer->getConfigurableBundleTemplateSlot();
        $this->assertNotNull($updatedConfigurableBundleTemplateSlotTransfer);
        $this->assertNotSame($configurableBundleTemplateSlotTransfer->getName(), $updatedConfigurableBundleTemplateSlotTransfer->getName());
    }

    /**
     * @return void
     */
    public function testUpdateConfigurableBundleTemplateSlotWillReturnNotSuccessfulResponseIfSlotNotFound(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);
        $configurableBundleTemplateSlotTransfer->setIdConfigurableBundleTemplateSlot(0);

        // Act
        $configurableBundleResponseTransfer = $this->tester
            ->getFacade()
            ->updateConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);

        // Assert
        $this->assertFalse($configurableBundleResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testDeleteConfigurableBundleTemplateSlotByIdDeletesSlot(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        // Act
        $this->tester->getFacade()->deleteConfigurableBundleTemplateSlotById(
            $configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot()
        );

        // Assert
        $configurableBundleTemplateSlotTransfer = $this->tester->getFacade()
            ->findConfigurableBundleTemplateSlot(
                (new ConfigurableBundleTemplateSlotFilterTransfer())->setIdConfigurableBundleTemplateSlot(
                    $configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot()
                )
            );

        $this->assertNull($configurableBundleTemplateSlotTransfer);
    }

    /**
     * @return void
     */
    public function testFindConfigurableBundleTemplateSlotWillReturnTransfer(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        $configurableBundleTemplateSlotFilterTransfer = (new ConfigurableBundleTemplateSlotFilterTransfer())->setIdConfigurableBundleTemplateSlot(
            $configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot()
        );

        // Act
        $foundConfigurableBundleTemplateSlotTransfer = $this->tester->getFacade()
            ->findConfigurableBundleTemplateSlot($configurableBundleTemplateSlotFilterTransfer);

        // Assert
        $this->assertNotNull($foundConfigurableBundleTemplateSlotTransfer);
        $this->assertSame(
            $foundConfigurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot(),
            $foundConfigurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot()
        );
        $this->assertSame(
            $foundConfigurableBundleTemplateSlotTransfer->getName(),
            $foundConfigurableBundleTemplateSlotTransfer->getName()
        );
    }

    /**
     * @return void
     */
    public function testFindConfigurableBundleTemplateSlotWillReturnNullIfSlotNotFound(): void
    {
        // Arrange
        $configurableBundleTemplateSlotFilterTransfer = (new ConfigurableBundleTemplateSlotFilterTransfer())->setIdConfigurableBundleTemplateSlot(-1);

        // Act
        $configurableBundleTemplateSlotTransfer = $this->tester->getFacade()
            ->findConfigurableBundleTemplateSlot($configurableBundleTemplateSlotFilterTransfer);

        // Assert
        $this->assertNull($configurableBundleTemplateSlotTransfer);
    }

    /**
     * @return void
     */
    public function testGetProductListIdByIdConfigurableBundleTemplateWillReturnId(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        // Act
        $idProductList = $this->tester->getFacade()->getProductListIdByIdConfigurableBundleTemplate(
            $configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot()
        );

        // Assert
        $this->assertSame($configurableBundleTemplateSlotTransfer->getProductList()->getIdProductList(), $idProductList);
    }
}
