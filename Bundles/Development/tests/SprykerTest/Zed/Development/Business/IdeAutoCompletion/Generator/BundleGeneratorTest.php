<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\IdeAutoCompletion\Generator;

use Codeception\Test\Unit;
use Spryker\Zed\Development\Business\IdeAutoCompletion\FileWriter;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Generator\BundleGenerator;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionConstants;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionOptionConstants;
use Twig\Environment;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group IdeAutoCompletion
 * @group Generator
 * @group BundleGeneratorTest
 * Add your own group annotations below this line
 */
class BundleGeneratorTest extends Unit
{
    /**
     * @return void
     */
    public function testTemplateNameIsDerivedFromGeneratorName()
    {
        $twigEnvironmentMock = $this->createTwigEnvironmentMock();
        $twigEnvironmentMock
            ->expects($this->once())
            ->method('render')
            ->with($this->equalTo('AutoCompletion.twig'))
            ->willReturn('');

        $generator = new BundleGenerator($twigEnvironmentMock, new FileWriter(), $this->getGeneratorOptions());
        $generator->generate([]);
    }

    /**
     * @return void
     */
    public function testNamespacePatternIsFilledWithApplicationName()
    {
        $twigEnvironmentMock = $this->createTwigEnvironmentMock();
        $twigEnvironmentMock
            ->expects($this->once())
            ->method('render')
            ->willReturnCallback(function ($templateName, $templateVariables) {
                $this->assertArrayHasKey('namespace', $templateVariables);
                $this->assertSame('Generated\FooApplication\Ide', $templateVariables['namespace']);

                return '';
            });

        $generator = new BundleGenerator($twigEnvironmentMock, new FileWriter(), $this->getGeneratorOptions());
        $generator->generate([]);
    }

    /**
     * @return array
     */
    protected function getGeneratorOptions()
    {
        return [
            IdeAutoCompletionOptionConstants::APPLICATION_NAME => 'FooApplication',
            IdeAutoCompletionOptionConstants::TARGET_BASE_DIRECTORY => sys_get_temp_dir(),
            IdeAutoCompletionOptionConstants::TARGET_DIRECTORY_PATTERN => '/',
            IdeAutoCompletionOptionConstants::TARGET_NAMESPACE_PATTERN => sprintf(
                'Generated\%s\Ide',
                IdeAutoCompletionConstants::APPLICATION_NAME_PLACEHOLDER
            ),
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Twig\Environment
     */
    protected function createTwigEnvironmentMock()
    {
        return $this
            ->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
