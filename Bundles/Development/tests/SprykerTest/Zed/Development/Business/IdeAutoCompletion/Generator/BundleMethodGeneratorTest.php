<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\IdeAutoCompletion\Generator;

use Codeception\Test\Unit;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Generator\BundleMethodGenerator;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionConstants;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionOptionConstants;
use Twig_Environment;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group IdeAutoCompletion
 * @group Generator
 * @group BundleMethodGeneratorTest
 * Add your own group annotations below this line
 */
class BundleMethodGeneratorTest extends Unit
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
            ->with($this->equalTo('BundleAutoCompletion.twig'));

        $generator = new BundleMethodGenerator($twigEnvironmentMock, $this->getGeneratorOptions());
        $generator->generate([]);
    }

    /**
     * @return array
     */
    protected function getGeneratorOptions()
    {
        return [
            IdeAutoCompletionOptionConstants::APPLICATION_NAME => 'BarApplication',
            IdeAutoCompletionOptionConstants::TARGET_NAMESPACE_PATTERN => sprintf(
                'Generated\%s\Ide',
                IdeAutoCompletionConstants::APPLICATION_NAME_PLACEHOLDER
            ),
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Twig_Environment
     */
    protected function createTwigEnvironmentMock()
    {
        return $this
            ->getMockBuilder(Twig_Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
