<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundle\Business\Generator;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGenerator;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Service\ConfigurableBundleToUtilTextServiceBridge;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ConfigurableBundle
 * @group Business
 * @group Generator
 * @group ConfigurableBundleNameGeneratorTest
 * Add your own group annotations below this line
 */
class ConfigurableBundleNameGeneratorTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ConfigurableBundle\ConfigurableBundleBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider getGenerateConfigurableBundleTemplateNameData
     *
     * @param string $rawName
     * @param string $expectedGeneratedName
     *
     * @return void
     */
    public function testSetConfigurableBundleTemplateName(string $rawName, string $expectedGeneratedName): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->createConfigurableBundleTemplateTransfer($rawName);

        // Act
        $templateName = $this->createConfigurableBundleNameGenerator()->generateTemplateName($configurableBundleTemplateTransfer);

        // Assert
        $this->assertSame($expectedGeneratedName, $templateName);
    }

    /**
     * @return array
     */
    public function getGenerateConfigurableBundleTemplateNameData(): array
    {
        return [
            ['My bundle', 'configurable_bundle.templates.my-bundle.name'],
            ['Alot    of    spaces', 'configurable_bundle.templates.alot-of-spaces.name'],
            ['another “template” example', 'configurable_bundle.templates.another-template-example.name'],
            ['Template 1', 'configurable_bundle.templates.template-1.name'],
            ['Example !@#$%^&&*()_ name', 'configurable_bundle.templates.example-name.name'],
        ];
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface
     */
    protected function createConfigurableBundleNameGenerator(): ConfigurableBundleNameGeneratorInterface
    {
        return new ConfigurableBundleNameGenerator($this->createConfigurableBundleToUtilTextServiceBridgeMock());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ConfigurableBundle\Dependency\Service\ConfigurableBundleToUtilTextServiceBridge
     */
    protected function createConfigurableBundleToUtilTextServiceBridgeMock(): ConfigurableBundleToUtilTextServiceBridge
    {
        return $this->getMockBuilder(ConfigurableBundleToUtilTextServiceBridge::class)
            ->setConstructorArgs([$this->tester->getLocator()->utilText()->service()])
            ->setMethods()
            ->getMock();
    }

    /**
     * @param string $firstTranslationName
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    protected function createConfigurableBundleTemplateTransfer(string $firstTranslationName): ConfigurableBundleTemplateTransfer
    {
        $firstConfigurableBundleTemplateTranslationTransfer = (new ConfigurableBundleTemplateTranslationTransfer())
            ->setName($firstTranslationName);

        $configurableBundleTemplateTranslationTransfers = $this->tester->createTemplateTranslationsForAvailableLocales();

        $configurableBundleTemplateTranslationTransfers = array_merge(
            [$firstConfigurableBundleTemplateTranslationTransfer],
            $configurableBundleTemplateTranslationTransfers
        );

        return (new ConfigurableBundleTemplateTransfer())
            ->setTranslations(new ArrayObject($configurableBundleTemplateTranslationTransfers));
    }
}
