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
    public function testFindConfigurableBundleTemplateByIdWillReturnNullIfTemplateNotFound(): void
    {
        // Act
        $configurableBundleTemplateTransfer = $this->tester->getFacade()->findConfigurableBundleTemplateById(0);

        // Assert
        $this->assertNull($configurableBundleTemplateTransfer);
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
    public function testUpdateConfigurableBundleTemplateWillReturnNotSuccessfulResponseIfTemplateNotFound(): void
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
        // Arrange
        $configurableBundleTemplateTransfer = $this->tester->createConfigurableBundleTemplateTransfer();

        // Act
        $configurableBundleTemplateResponseTransfer = $this->tester
            ->getFacade()
            ->createConfigurableBundleTemplate($configurableBundleTemplateTransfer);

        // Assert
        $this->assertInstanceOf(ConfigurableBundleTemplateResponseTransfer::class, $configurableBundleTemplateResponseTransfer);
        $this->assertTrue($configurableBundleTemplateResponseTransfer->getIsSuccessful());
        $configurableBundleTemplateTransfer = $configurableBundleTemplateResponseTransfer->getConfigurableBundleTemplate();
        $this->assertInstanceOf(ConfigurableBundleTemplateTransfer::class, $configurableBundleTemplateTransfer);
        $this->assertGreaterThan(0, $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());
    }
}
