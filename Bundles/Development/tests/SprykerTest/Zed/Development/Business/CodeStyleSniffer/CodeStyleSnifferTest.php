<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\CodeStyleSniffer;

use Codeception\Test\Unit;
use Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group CodeStyleSniffer
 * @group CodeStyleSnifferTest
 * Add your own group annotations below this line
 */
class CodeStyleSnifferTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Development\DevelopmentBusinessTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected $pathToCore = 'vendor/spryker/spryker/Bundles/';

    /**
     * The list of default CodeStyleSniffer options.
     *
     * @var array
     */
    protected $defaultOptions = [
        'module' => null,
        'sniffs' => null,
        'level' => 1,
        'explain' => false,
        'dry-run' => false,
        'fix' => false,
        'help' => false,
        'quiet' => false,
        'verbose' => false,
        'version' => false,
        'ansi' => false,
        'no-ansi' => false,
        'no-interaction' => false,
        'no-pre' => false,
        'no-post' => false,
        'path' => null,
    ];

    /**
     * @return void
     */
    public function testCheckCodeStyleRunsCommandInProject()
    {
        $options = ['ignore' => 'vendor/'] + $this->defaultOptions;
        $pathToApplicationRoot = APPLICATION_ROOT_DIR . '/';
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($pathToApplicationRoot, $options);

        $codeStyleSnifferMock->checkCodeStyle(null, $options);
    }

    /**
     * @return void
     */
    public function testCheckCodeStyleRunsCommandInProjectModule()
    {
        $options = ['ignore' => 'vendor/'] + $this->defaultOptions;
        $pathToApplicationRoot = APPLICATION_ROOT_DIR . '/src/Pyz/Zed/Development/';
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($pathToApplicationRoot, $options);

        $codeStyleSnifferMock->checkCodeStyle('Development', $options);
    }

    /**
     * @return void
     */
    public function testCheckCodeStyleRunsCommandInCore()
    {
        $module = 'Spryker.all';
        $options = [
            'ignore' => 'vendor/',
            'module' => $module,
        ] + $this->defaultOptions;

        $path = APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . $this->pathToCore;
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($path, $options);

        $codeStyleSnifferMock->checkCodeStyle($module, $options);
    }

    /**
     * @return void
     */
    public function testCheckCodeStyleRunsCommandInCoreModuleForLevelOne()
    {
        $module = 'Spryker.Development';
        $options = [
            'ignore' => 'vendor/',
            'module' => $module,
            'level' => 1,
        ] + $this->defaultOptions;

        $path = APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . $this->pathToCore . 'Development/';
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($path, $options);

        $codeStyleSnifferMock->checkCodeStyle($module, $options);
    }

    /**
     * @return void
     */
    public function testCheckCodeStyleRunsCommandInCoreModuleForLevelTwo()
    {
        $module = 'Spryker.Development';
        $options = [
            'ignore' => 'vendor/',
            'module' => $module,
            'level' => 2,
        ] + $this->defaultOptions;

        $path = APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . $this->pathToCore . 'Development/';
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($path, $options);

        $codeStyleSnifferMock->checkCodeStyle($module, $options);
    }

    /**
     * @param string $expectedPathToRunCommandWith
     * @param array $options
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer
     */
    protected function getCodeStyleSnifferMock($expectedPathToRunCommandWith, array $options)
    {
        $developmentConfig = $this->tester->createDevelopmentConfig();
        $codingStandard = $developmentConfig->getCodingStandard();

        if ($options['level'] === 2) {
            /** @see \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfiguration::getCodingStandard() */
            $codingStandard = APPLICATION_VENDOR_DIR . DIRECTORY_SEPARATOR . 'spryker/code-sniffer/SprykerStrict/ruleset.xml';
        }

        $codeStyleSnifferMock = $this
            ->getMockBuilder(CodeStyleSniffer::class)
            ->setConstructorArgs([
                $developmentConfig,
                $this->tester->createCodeStyleSnifferConfigurationLoader(),
            ])
            ->setMethods(['runSnifferCommand'])
            ->getMock();

        $codeStyleSnifferMock
            ->method('runSnifferCommand')
            ->with(
                $expectedPathToRunCommandWith,
                $this->callback(function ($subject) use ($codingStandard) {
                    return is_callable([$subject, 'getCodingStandard']) && $subject->getCodingStandard() === $codingStandard;
                })
            );

        return $codeStyleSnifferMock;
    }
}
