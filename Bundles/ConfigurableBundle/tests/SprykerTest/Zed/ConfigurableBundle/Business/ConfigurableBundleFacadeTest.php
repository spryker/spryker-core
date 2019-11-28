<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundle\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTranslationTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
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
    protected const FAKE_TEMPLATE_ID = 666;
    protected const FAKE_TEMPLATE_SLOT_ID = 666;
    protected const FAKE_PRODUCT_LIST_ID = 666;

    protected const FAKE_PRODUCT_IMAGE_SET_NAME_1 = 'FAKE_PRODUCT_IMAGE_SET_NAME_1';
    protected const FAKE_PRODUCT_IMAGE_SET_NAME_2 = 'FAKE_PRODUCT_IMAGE_SET_NAME_2';

    /**
     * @see \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReader::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_EXISTS
     */
    protected const GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_EXISTS = 'configurable_bundle.template.validation.error.not_exists';

    /**
     * @see \Spryker\Zed\ConfigurableBundle\Business\Updater\ConfigurableBundleTemplateUpdater::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_ALREADY_ACTIVATED
     */
    protected const GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_ALREADY_ACTIVATED = 'configurable_bundle.template.validation.error.already_activated';

    /**
     * @see \Spryker\Zed\ConfigurableBundle\Business\Updater\ConfigurableBundleTemplateUpdater::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_ALREADY_DEACTIVATED
     */
    protected const GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_ALREADY_DEACTIVATED = 'configurable_bundle.template.validation.error.already_deactivated';

    /**
     * @see \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReader::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NOT_EXISTS
     */
    protected const GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NOT_EXISTS = 'configurable_bundle.slot.validation.error.not_exists';

    /**
     * @var \SprykerTest\Zed\ConfigurableBundle\ConfigurableBundleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateRetrievesTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();

        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);

        // Assert
        $this->assertTrue($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $this->assertSame(
            $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
            $configurableBundleTemplateResponseTransfer->getConfigurableBundleTemplate()->getIdConfigurableBundleTemplate()
        );
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateRetrievesTemplateWithWrongId(): void
    {
        // Arrange

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplate(
                (new ConfigurableBundleTemplateFilterTransfer())->setIdConfigurableBundleTemplate(static::FAKE_TEMPLATE_ID)
            );

        // Assert
        $this->assertFalse($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_EXISTS,
            $configurableBundleTemplateResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateRetrievesTemplateWithExpandedTranslations(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();

        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);

        // Assert
        $this->assertTrue($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            $configurableBundleTemplateTransfer->getTranslations(),
            $configurableBundleTemplateResponseTransfer->getConfigurableBundleTemplate()->getTranslations()
        );
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateCollectionRetrievesTemplateCollection(): void
    {
        // Arrange
        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->addConfigurableBundleTemplateId(
                $this->tester
                    ->createActiveConfigurableBundleTemplate()
                    ->getIdConfigurableBundleTemplate()
            )
            ->addConfigurableBundleTemplateId(
                $this->tester
                    ->createActiveConfigurableBundleTemplate()
                    ->getIdConfigurableBundleTemplate()
            );

        // Act
        $configurableBundleTemplateCollectionTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplateCollection($configurableBundleTemplateFilterTransfer);

        // Assert
        $this->assertCount(2, $configurableBundleTemplateCollectionTransfer->getConfigurableBundleTemplates());
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateCollectionRetrievesTemplateCollectionWithWrongId(): void
    {
        // Arrange
        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->setIdConfigurableBundleTemplate(static::FAKE_TEMPLATE_ID);

        // Act
        $configurableBundleTemplateCollectionTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplateCollection($configurableBundleTemplateFilterTransfer);

        // Assert
        $this->assertEmpty($configurableBundleTemplateCollectionTransfer->getConfigurableBundleTemplates());
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateCollectionRetrievesTemplateCollectionWithExpandedTranslations(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();

        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        // Act
        $configurableBundleTemplateCollectionTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplateCollection($configurableBundleTemplateFilterTransfer);

        // Assert
        $this->assertEquals(
            $configurableBundleTemplateTransfer->getTranslations(),
            $configurableBundleTemplateCollectionTransfer->getConfigurableBundleTemplates()->offsetGet(0)->getTranslations()
        );
    }

    /**
     * @return void
     */
    public function testCreateConfigurableBundleTemplateCreatesTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createConfigurableBundleTemplateTransfer();

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->createConfigurableBundleTemplate($configurableBundleTemplateTransfer);

        // Assert
        $this->assertTrue($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $this->assertNotNull($configurableBundleTemplateResponseTransfer->getConfigurableBundleTemplate()->getIdConfigurableBundleTemplate());
        $this->assertSame(
            $configurableBundleTemplateTransfer->getName(),
            $configurableBundleTemplateResponseTransfer->getConfigurableBundleTemplate()->getName()
        );
    }

    /**
     * @return void
     */
    public function testCreateConfigurableBundleTemplateCreatesTemplateWithoutProvidedTranslations(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester
            ->createConfigurableBundleTemplateTransfer()
            ->setTranslations(new ArrayObject());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->createConfigurableBundleTemplate($configurableBundleTemplateTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateConfigurableBundleTemplateUpdatesTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateTransfer->setTranslations(
            $this->tester->createTemplateTranslationTransfersForAvailableLocales([
                ConfigurableBundleTemplateTranslationTransfer::NAME => 'template new name',
            ])
        );

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->updateConfigurableBundleTemplate($configurableBundleTemplateTransfer);

        // Assert
        $this->assertTrue($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            $configurableBundleTemplateTransfer->getTranslations(),
            $configurableBundleTemplateResponseTransfer->getConfigurableBundleTemplate()->getTranslations()
        );
    }

    /**
     * @return void
     */
    public function testUpdateConfigurableBundleTemplateUpdatesTemplateWithoutProvidedId(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester
            ->createConfigurableBundleTemplateTransfer()
            ->setIdConfigurableBundleTemplate(static::FAKE_TEMPLATE_ID);

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->updateConfigurableBundleTemplate($configurableBundleTemplateTransfer);

        // Assert
        $this->assertFalse($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_EXISTS,
            $configurableBundleTemplateResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testUpdateConfigurableBundleTemplateUpdatesTemplateWithoutProvidedTranslations(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester
            ->createConfigurableBundleTemplateTransfer()
            ->setTranslations(new ArrayObject());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->updateConfigurableBundleTemplate($configurableBundleTemplateTransfer);
    }

    /**
     * @return void
     */
    public function testActivateConfigurableBundleTemplateActivatesTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createDeactivatedConfigurableBundleTemplate();

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->activateConfigurableBundleTemplate(
                (new ConfigurableBundleTemplateFilterTransfer())
                    ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            );

        // Assert
        $this->assertTrue($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $this->assertTrue($configurableBundleTemplateResponseTransfer->getConfigurableBundleTemplate()->getIsActive());
    }

    /**
     * @return void
     */
    public function testActivateConfigurableBundleTemplateActivatesTemplateWithWithoutProvidedId(): void
    {
        // Arrange

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->activateConfigurableBundleTemplate(
                (new ConfigurableBundleTemplateFilterTransfer())
                    ->setIdConfigurableBundleTemplate(static::FAKE_TEMPLATE_ID)
            );

        // Assert
        $this->assertFalse($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_EXISTS,
            $configurableBundleTemplateResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testActivateConfigurableBundleTemplateActivatesTemplateAlreadyActiveTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->activateConfigurableBundleTemplate(
                (new ConfigurableBundleTemplateFilterTransfer())
                    ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            );

        // Assert
        $this->assertFalse($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_ALREADY_ACTIVATED,
            $configurableBundleTemplateResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testDeactivateConfigurableBundleTemplateDeactivatesTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->deactivateConfigurableBundleTemplate(
                (new ConfigurableBundleTemplateFilterTransfer())
                    ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            );

        // Assert
        $this->assertTrue($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $this->assertFalse($configurableBundleTemplateResponseTransfer->getConfigurableBundleTemplate()->getIsActive());
    }

    /**
     * @return void
     */
    public function testDeactivateConfigurableBundleTemplateDeactivatesTemplateWithoutProvidedId(): void
    {
        // Arrange

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->deactivateConfigurableBundleTemplate(
                (new ConfigurableBundleTemplateFilterTransfer())
                    ->setIdConfigurableBundleTemplate(static::FAKE_TEMPLATE_ID)
            );

        // Assert
        $this->assertFalse($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_EXISTS,
            $configurableBundleTemplateResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testDeactivateConfigurableBundleTemplateDeactivatesTemplateAlreadyDeactivatedTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createDeactivatedConfigurableBundleTemplate();

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->deactivateConfigurableBundleTemplate(
                (new ConfigurableBundleTemplateFilterTransfer())
                    ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            );

        // Assert
        $this->assertFalse($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_ALREADY_DEACTIVATED,
            $configurableBundleTemplateResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testDeleteConfigurableBundleTemplateDeletesTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->deleteConfigurableBundleTemplate(
                (new ConfigurableBundleTemplateFilterTransfer())
                    ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            );

        // Assert
        $this->assertTrue($configurableBundleTemplateResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testDeleteConfigurableBundleTemplateDeletesTemplateWithoutProvidedId(): void
    {
        // Arrange

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->deleteConfigurableBundleTemplate(
                (new ConfigurableBundleTemplateFilterTransfer())
                    ->setIdConfigurableBundleTemplate(static::FAKE_TEMPLATE_ID)
            );

        // Assert
        $this->assertFalse($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_EXISTS,
            $configurableBundleTemplateResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testDeleteConfigurableBundleTemplateDeletesTemplateSlots(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->deleteConfigurableBundleTemplate(
                (new ConfigurableBundleTemplateFilterTransfer())
                    ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            );

        // Assert
        $configurableBundleTemplateSlotResponseTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplateSlot(
                (new ConfigurableBundleTemplateSlotFilterTransfer())
                    ->setIdConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot())
            );

        $this->assertTrue($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $this->assertFalse($configurableBundleTemplateSlotResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateSlotRetrievesSlot(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        // Act
        $configurableBundleTemplateSlotResponseTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplateSlot(
                (new ConfigurableBundleTemplateSlotFilterTransfer())
                    ->setIdConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot())
            );

        // Assert
        $this->assertTrue($configurableBundleTemplateSlotResponseTransfer->getIsSuccessful());
        $this->assertSame(
            $configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot(),
            $configurableBundleTemplateSlotResponseTransfer->getConfigurableBundleTemplateSlot()->getIdConfigurableBundleTemplateSlot()
        );
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateSlotRetrievesSlotWithoutProvidedId(): void
    {
        // Arrange

        // Act
        $configurableBundleTemplateSlotResponseTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplateSlot(
                (new ConfigurableBundleTemplateSlotFilterTransfer())
                    ->setIdConfigurableBundleTemplateSlot(static::FAKE_TEMPLATE_SLOT_ID)
            );

        // Assert
        $this->assertFalse($configurableBundleTemplateSlotResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NOT_EXISTS,
            $configurableBundleTemplateSlotResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateSlotRetrievesSlotWithExpandedTemplate(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        // Act
        $configurableBundleTemplateSlotResponseTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplateSlot(
                (new ConfigurableBundleTemplateSlotFilterTransfer())
                    ->setIdConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot())
            );

        // Assert
        $this->assertTrue($configurableBundleTemplateSlotResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            $configurableBundleTemplateTransfer,
            $configurableBundleTemplateSlotResponseTransfer->getConfigurableBundleTemplateSlot()->getConfigurableBundleTemplate()
        );
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateSlotRetrievesSlotWithExpandedProductList(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        // Act
        $configurableBundleTemplateSlotResponseTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplateSlot(
                (new ConfigurableBundleTemplateSlotFilterTransfer())
                    ->setIdConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot())
            );

        // Assert
        $this->assertTrue($configurableBundleTemplateSlotResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            $configurableBundleTemplateSlotTransfer->getProductList(),
            $configurableBundleTemplateSlotResponseTransfer->getConfigurableBundleTemplateSlot()->getProductList()
        );
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateSlotRetrievesSlotWithExpandedTranslations(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        // Act
        $configurableBundleTemplateSlotResponseTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplateSlot(
                (new ConfigurableBundleTemplateSlotFilterTransfer())
                    ->setIdConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot())
            );

        // Assert
        $this->assertTrue($configurableBundleTemplateSlotResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            $configurableBundleTemplateSlotTransfer->getTranslations(),
            $configurableBundleTemplateSlotResponseTransfer->getConfigurableBundleTemplateSlot()->getTranslations()
        );
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateSlotCollectionRetrievesSlotCollection(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        $configurableBundleTemplateSlotFilterTransfer = (new ConfigurableBundleTemplateSlotFilterTransfer())
            ->setIdConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot());

        // Act
        $configurableBundleTemplateSlotCollectionTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplateSlotCollection($configurableBundleTemplateSlotFilterTransfer);

        // Assert
        $this->assertCount(1, $configurableBundleTemplateSlotCollectionTransfer->getConfigurableBundleTemplateSlots());
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateSlotCollectionRetrievesSlotCollectionWithoutProvidedId(): void
    {
        // Arrange
        $configurableBundleTemplateSlotFilterTransfer = (new ConfigurableBundleTemplateSlotFilterTransfer())
            ->setIdConfigurableBundleTemplateSlot(static::FAKE_TEMPLATE_SLOT_ID);

        // Act
        $configurableBundleTemplateSlotCollectionTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplateSlotCollection($configurableBundleTemplateSlotFilterTransfer);

        // Assert
        $this->assertEmpty($configurableBundleTemplateSlotCollectionTransfer->getConfigurableBundleTemplateSlots());
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateSlotCollectionRetrievesSlotCollectionWithExpandedProductList(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        // Act
        $configurableBundleTemplateSlotCollectionTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplateSlotCollection(
                (new ConfigurableBundleTemplateSlotFilterTransfer())
                    ->setIdConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot())
            );

        // Assert
        $this->assertEquals(
            $configurableBundleTemplateSlotTransfer->getProductList(),
            $configurableBundleTemplateSlotCollectionTransfer->getConfigurableBundleTemplateSlots()->offsetGet(0)->getProductList()
        );
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateSlotCollectionRetrievesSlotCollectionWithExpandedTranslations(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        // Act
        $configurableBundleTemplateSlotCollectionTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplateSlotCollection(
                (new ConfigurableBundleTemplateSlotFilterTransfer())
                    ->setIdConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot())
            );

        // Assert
        $this->assertEquals(
            $configurableBundleTemplateSlotTransfer->getTranslations(),
            $configurableBundleTemplateSlotCollectionTransfer->getConfigurableBundleTemplateSlots()->offsetGet(0)->getTranslations()
        );
    }

    /**
     * @return void
     */
    public function testCreateConfigurableBundleTemplateSlotCreatesSlot(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester
            ->createConfigurableBundleTemplateSlotTransfer()
            ->setFkConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        // Act
        $configurableBundleTemplateSlotResponseTransfer = $this->tester
            ->getFacade()
            ->createConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);

        // Assert
        $this->assertTrue($configurableBundleTemplateSlotResponseTransfer->getIsSuccessful());
        $this->assertNotNull($configurableBundleTemplateSlotResponseTransfer->getConfigurableBundleTemplateSlot()->getIdConfigurableBundleTemplateSlot());
        $this->assertSame(
            $configurableBundleTemplateSlotTransfer->getName(),
            $configurableBundleTemplateSlotResponseTransfer->getConfigurableBundleTemplateSlot()->getName()
        );
    }

    /**
     * @return void
     */
    public function testCreateConfigurableBundleTemplateSlotCreatesSlotWithoutProvidedTemplateId(): void
    {
        // Arrange
        $configurableBundleTemplateSlotTransfer = $this->tester
            ->createConfigurableBundleTemplateSlotTransfer()
            ->setFkConfigurableBundleTemplate(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->createConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);
    }

    /**
     * @return void
     */
    public function testCreateConfigurableBundleTemplateSlotCreatesSlotWithoutProvidedTranslations(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester
            ->createConfigurableBundleTemplateSlotTransfer()
            ->setFkConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            ->setTranslations(new ArrayObject());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->createConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateConfigurableBundleTemplateSlotUpdatesSlot(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        $configurableBundleTemplateSlotTransfer->setTranslations(
            $this->tester->createSlotTranslationTransfersForAvailableLocales([
                ConfigurableBundleTemplateSlotTranslationTransfer::NAME => 'slot new name',
            ])
        );

        // Act
        $configurableBundleTemplateSlotResponseTransfer = $this->tester
            ->getFacade()
            ->updateConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);

        // Assert
        $this->assertTrue($configurableBundleTemplateSlotResponseTransfer->getIsSuccessful());
        $this->assertSame(
            $configurableBundleTemplateSlotTransfer->getTranslations(),
            $configurableBundleTemplateSlotResponseTransfer->getConfigurableBundleTemplateSlot()->getTranslations()
        );
    }

    /**
     * @return void
     */
    public function testUpdateConfigurableBundleTemplateSlotUpdatesSlotWithoutProvidedId(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester
            ->createConfigurableBundleTemplateSlotTransfer()
            ->setFkConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            ->setIdConfigurableBundleTemplateSlot(static::FAKE_TEMPLATE_SLOT_ID);

        // Act
        $configurableBundleTemplateSlotResponseTransfer = $this->tester
            ->getFacade()
            ->updateConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);

        // Assert
        $this->assertFalse($configurableBundleTemplateSlotResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NOT_EXISTS,
            $configurableBundleTemplateSlotResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testDeleteConfigurableBundleTemplateSlotDeletesSlot(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        // Act
        $configurableBundleTemplateSlotResponseTransfer = $this->tester
            ->getFacade()
            ->deleteConfigurableBundleTemplateSlot(
                (new ConfigurableBundleTemplateSlotFilterTransfer())
                    ->setIdConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot())
            );

        // Assert
        $this->assertTrue($configurableBundleTemplateSlotResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testDeleteConfigurableBundleTemplateSlotDeletesSlotWithoutProvidedId(): void
    {
        // Arrange

        // Act
        $configurableBundleTemplateSlotResponseTransfer = $this->tester
            ->getFacade()
            ->deleteConfigurableBundleTemplateSlot(
                (new ConfigurableBundleTemplateSlotFilterTransfer())
                    ->setIdConfigurableBundleTemplateSlot(static::FAKE_TEMPLATE_SLOT_ID)
            );

        // Assert
        $this->assertFalse($configurableBundleTemplateSlotResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NOT_EXISTS,
            $configurableBundleTemplateSlotResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testRemoveInactiveConfiguredBundleItemsFromQuoteCleanUpsQuote(): void
    {
        // Arrange
        $activatedConfigurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $deactivatedConfigurableBundleTemplateTransfer = $this->tester->createDeactivatedConfigurableBundleTemplate();
        $quoteTransfer = (new QuoteTransfer())
            ->addItem(
                (new ItemTransfer())
                    ->setConfiguredBundle(
                        (new ConfiguredBundleTransfer())
                            ->setTemplate((new ConfigurableBundleTemplateTransfer())->setUuid($activatedConfigurableBundleTemplateTransfer->getUuid()))
                    )
            )
            ->addItem(
                (new ItemTransfer())
                    ->setConfiguredBundle(
                        (new ConfiguredBundleTransfer())
                            ->setTemplate((new ConfigurableBundleTemplateTransfer())->setUuid($deactivatedConfigurableBundleTemplateTransfer->getUuid()))
                    )
            )
            ->addItem(
                (new ItemTransfer())
                    ->setConfiguredBundle(
                        (new ConfiguredBundleTransfer())
                            ->setTemplate((new ConfigurableBundleTemplateTransfer())->setUuid(static::FAKE_TEMPLATE_SLOT_ID))
                    )
            );

        // Act
        $quoteTransfer = $this->tester
            ->getFacade()
            ->removeInactiveConfiguredBundleItemsFromQuote($quoteTransfer);

        // Assert
        $this->assertCount(1, $quoteTransfer->getItems());
        $this->assertSame(
            $activatedConfigurableBundleTemplateTransfer->getUuid(),
            $quoteTransfer->getItems()->offsetGet(0)->getConfiguredBundle()->getTemplate()->getUuid()
        );
    }

    /**
     * @return void
     */
    public function testRemoveInactiveConfiguredBundleItemsFromQuoteCleanUpsQuoteWithoutConfiguredBundleProperties(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->addItem(new ItemTransfer())
            ->addItem(new ItemTransfer())
            ->addItem(new ItemTransfer());

        // Act
        $quoteTransfer = $this->tester
            ->getFacade()
            ->removeInactiveConfiguredBundleItemsFromQuote($quoteTransfer);

        // Assert
        $this->assertCount(3, $quoteTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testIsProductListDeletableChecksRelationshipsBetweenProductListAndSlots(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $configurableBundleTemplateSlotTransfer = $this->tester->createConfigurableBundleTemplateSlot([
            ConfigurableBundleTemplateSlotTransfer::FK_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        // Act
        $productListResponseTransfer = $this->tester
            ->getFacade()
            ->isProductListDeletable(
                (new ConfigurableBundleTemplateSlotFilterTransfer())
                    ->setProductList($configurableBundleTemplateSlotTransfer->getProductList())
                    ->setTranslationLocales(new ArrayObject([$this->tester->getLocaleFacade()->getCurrentLocale()]))
            );

        // Assert
        $this->assertFalse($productListResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $productListResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testIsProductListDeletableChecksRelationshipsBetweenProductListAndSlotsWithoutProvidedId(): void
    {
        // Arrange

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->isProductListDeletable(
                (new ConfigurableBundleTemplateSlotFilterTransfer())
                    ->setProductList(new ProductListTransfer())
                    ->setTranslationLocales(new ArrayObject([$this->tester->getLocaleFacade()->getCurrentLocale()]))
            );
    }

    /**
     * @return void
     */
    public function testIsProductListDeletableChecksRelationshipsBetweenProductListAndNotFoundSlots(): void
    {
        // Arrange

        // Act
        $productListResponseTransfer = $this->tester
            ->getFacade()
            ->isProductListDeletable(
                (new ConfigurableBundleTemplateSlotFilterTransfer())
                    ->setProductList((new ProductListTransfer())->setIdProductList(static::FAKE_PRODUCT_LIST_ID))
                    ->setTranslationLocales(new ArrayObject([$this->tester->getLocaleFacade()->getCurrentLocale()]))
            );

        // Assert
        $this->assertTrue($productListResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateRetrievesTemplateWithCombinedImageSets(): void
    {
        // Arrange
        $locale = $this->tester->getLocaleFacade()->getCurrentLocale();
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::FK_RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
            ProductImageSetTransfer::LOCALE => null,
            ProductImageSetTransfer::NAME => static::FAKE_PRODUCT_IMAGE_SET_NAME_1,
        ]);

        $localizedProductImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::FK_RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            ->setTranslationLocales(new ArrayObject([$locale]));

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);

        // Assert
        $productImageSetTransfers = $configurableBundleTemplateResponseTransfer
            ->getConfigurableBundleTemplate()
            ->getProductImageSets();

        $this->assertTrue($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $this->assertCount(2, $productImageSetTransfers);
        $this->assertSame($productImageSetTransfer->toArray(), $productImageSetTransfers->offsetGet(0)->toArray());
        $this->assertSame($localizedProductImageSetTransfer->toArray(), $productImageSetTransfers->offsetGet(1)->toArray());
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateRetrievesTemplateWithDefaultImageSets(): void
    {
        // Arrange
        $locale = $this->tester->getLocaleFacade()->getCurrentLocale();
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::FK_RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
            ProductImageSetTransfer::LOCALE => null,
        ]);

        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            ->setTranslationLocales(new ArrayObject([$locale]));

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);

        // Assert
        $productImageSetTransfers = $configurableBundleTemplateResponseTransfer
            ->getConfigurableBundleTemplate()
            ->getProductImageSets();

        $this->assertTrue($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $productImageSetTransfers);
        $this->assertSame($productImageSetTransfer->toArray(), $productImageSetTransfers->offsetGet(0)->toArray());
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateRetrievesTemplateWithTwoImageSets(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::FK_RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
            ProductImageSetTransfer::NAME => static::FAKE_PRODUCT_IMAGE_SET_NAME_1,
        ]);
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::FK_RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
            ProductImageSetTransfer::NAME => static::FAKE_PRODUCT_IMAGE_SET_NAME_2,
        ]);

        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);

        $productImageSetTransfers = $configurableBundleTemplateResponseTransfer
            ->getConfigurableBundleTemplate()
            ->getProductImageSets();

        // Assert
        $this->assertTrue($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $this->assertCount(2, $productImageSetTransfers);
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateRetrievesTemplateWithOrderedImages(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();

        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::FK_RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
            ProductImageSetTransfer::PRODUCT_IMAGES => [
                $this->tester->createProductImageTransferWithSortOrder(12),
                $this->tester->createProductImageTransferWithSortOrder(5),
                $this->tester->createProductImageTransferWithSortOrder(8),
            ],
        ]);

        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);

        $productImageSetTransfers = $configurableBundleTemplateResponseTransfer
            ->getConfigurableBundleTemplate()
            ->getProductImageSets();

        /** @var \ArrayObject|\Generated\Shared\Transfer\ProductImageTransfer[] $productImageTransfers */
        $productImageTransfers = $productImageSetTransfers->offsetGet(0)->getProductImages();

        // Assert
        $this->assertTrue($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $this->assertCount(3, $productImageTransfers);
        $this->assertSame(5, $productImageTransfers->offsetGet(0)->getSortorder());
        $this->assertSame(8, $productImageTransfers->offsetGet(1)->getSortorder());
        $this->assertSame(12, $productImageTransfers->offsetGet(2)->getSortorder());
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateCollectionRetrievesTemplatesWithImageSets(): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::FK_RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate(),
        ]);

        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        // Act
        $configurableBundleTemplateCollectionTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplateCollection($configurableBundleTemplateFilterTransfer);

        $configurableBundleTemplateTransfer = $configurableBundleTemplateCollectionTransfer
            ->getConfigurableBundleTemplates()
            ->offsetGet(0);

        // Assert
        $this->assertCount(1, $configurableBundleTemplateCollectionTransfer->getConfigurableBundleTemplates());
        $this->assertEquals(
            $productImageSetTransfer->toArray(),
            $configurableBundleTemplateTransfer->getProductImageSets()->offsetGet(0)->toArray()
        );
    }

    /**
     * @return void
     */
    public function testGetConfigurableBundleTemplateCollectionRetrievesTemplatesWithLimit(): void
    {
        // Arrange
        $firstConfigurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();
        $secondConfigurableBundleTemplateTransfer = $this->tester->createActiveConfigurableBundleTemplate();

        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->addConfigurableBundleTemplateId($firstConfigurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            ->addConfigurableBundleTemplateId($secondConfigurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            ->setFilter((new FilterTransfer())->setLimit(1));

        // Act
        $configurableBundleTemplateCollectionTransfer = $this->tester
            ->getFacade()
            ->getConfigurableBundleTemplateCollection($configurableBundleTemplateFilterTransfer);

        // Assert
        $this->assertCount(1, $configurableBundleTemplateCollectionTransfer->getConfigurableBundleTemplates());
    }
}
