<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundle\Business\Generator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleTemplateNameGenerator;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleTemplateNameGeneratorInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ConfigurableBundle
 * @group Business
 * @group Generator
 * @group ConfigurableBundleTemplateNameGeneratorTest
 * Add your own group annotations below this line
 */
class ConfigurableBundleTemplateNameGeneratorTest extends Unit
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
    public function testGenerateConfigurableBundleTemplateName(string $rawName, string $expectedGeneratedName): void
    {
        // Arrange
        $configurableBundleTemplateTransfer = $this->createConfigurableBundleTemplateTransfer($rawName);

        // Act
        $configurableBundleTemplateTransfer = $this->createConfigurableBundleTemplateNameGenerator()
            ->setConfigurableBundleTemplateName($configurableBundleTemplateTransfer);

        // Assert
        $this->assertSame($expectedGeneratedName, $configurableBundleTemplateTransfer->getName());
    }

    /**
     * @return array
     */
    public function getGenerateConfigurableBundleTemplateNameData(): array
    {
        return [
            ['My bundle', 'configurable_bundle.template.my_bundle.name'],
            ['Alot    of    spaces', 'configurable_bundle.template.alot_of_spaces.name'],
            ['another "template" example', 'configurable_bundle.template.another_template_example.name'],
            ['Template 1', 'configurable_bundle.template.template_1.name'],
            ['Example !@#$%^&&*() name', 'configurable_bundle.template.example_name.name'],
            ['Allowed_name-symbols', 'configurable_bundle.template.allowed_name-symbols.name'],
        ];
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleTemplateNameGeneratorInterface
     */
    protected function createConfigurableBundleTemplateNameGenerator(): ConfigurableBundleTemplateNameGeneratorInterface
    {
        return new ConfigurableBundleTemplateNameGenerator();
    }

    /**
     * @param string $firstTranslationName
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    protected function createConfigurableBundleTemplateTransfer(string $firstTranslationName): ConfigurableBundleTemplateTransfer
    {
        $firstConfigurableBundleTemplateTranslationTransfer = (new ConfigurableBundleTemplateTranslationTransfer())->setName($firstTranslationName);
        $configurableBundleTemplateTranslationTransfers = $this->tester->createTranslationTransfersForAvailableLocales();

        $configurableBundleTemplateTranslationTransfers->exchangeArray(
            array_merge([$firstConfigurableBundleTemplateTranslationTransfer], $configurableBundleTemplateTranslationTransfers->getArrayCopy())
        );

        return (new ConfigurableBundleTemplateTransfer())->setTranslations($configurableBundleTemplateTranslationTransfers);
    }
}
