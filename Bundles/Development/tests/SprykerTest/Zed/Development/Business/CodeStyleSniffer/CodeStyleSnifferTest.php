<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\CodeStyleSniffer;

use Codeception\Test\Unit;
use Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer;
use Spryker\Zed\Development\DevelopmentConfig;

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
     * @var string
     */
    protected $pathToCore = 'vendor/spryker/spryker/Bundles/';

    /**
     * @return void
     */
    public function testCheckCodeStyleRunsCommandInProject()
    {
        $options = [
            'ignore' => 'vendor/',
        ];
        $pathToApplicationRoot = APPLICATION_ROOT_DIR . '/';
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($pathToApplicationRoot, $options);

        $codeStyleSnifferMock->checkCodeStyle(null);
    }

    /**
     * @return void
     */
    public function testCheckCodeStyleRunsCommandInProjectModule()
    {
        $options = [
            'ignore' => 'vendor/',
        ];
        $pathToApplicationRoot = APPLICATION_ROOT_DIR . '/src/Pyz/Zed/Development/';
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($pathToApplicationRoot, $options);

        $codeStyleSnifferMock->checkCodeStyle('Development');
    }

    /**
     * @return void
     */
    public function testCheckCodeStyleRunsCommandInCore()
    {
        $module = 'Spryker.all';
        $options = [
            'ignore' => '',
        ];

        $path = APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . $this->pathToCore;
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($path, $options);

        $codeStyleSnifferMock->checkCodeStyle($module, $options);
    }

    /**
     * @return void
     */
    public function testCheckCodeStyleRunsCommandInCoreModule()
    {
        $module = 'Spryker.Development';
        $options = [
            'ignore' => '',
        ];
        $path = APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . $this->pathToCore . 'Development/';
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($path, $options);

        $codeStyleSnifferMock->checkCodeStyle($module);
    }

    /**
     * @param string $expectedPathToRunCommandWith
     * @param array $options
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer
     */
    protected function getCodeStyleSnifferMock($expectedPathToRunCommandWith, array $options)
    {
        $developmentConfigMock = new DevelopmentConfig();

        $codeStyleSnifferMockBuilder = $this->getMockBuilder(CodeStyleSniffer::class);
        $codeStyleSnifferMockBuilder->setConstructorArgs([
            $developmentConfigMock,
        ]);
        $codeStyleSnifferMockBuilder->setMethods(['runSnifferCommand']);

        $codeStyleSnifferMock = $codeStyleSnifferMockBuilder->getMock();
        $codeStyleSnifferMock->method('runSnifferCommand')->with($expectedPathToRunCommandWith, $options);

        return $codeStyleSnifferMock;
    }
}
