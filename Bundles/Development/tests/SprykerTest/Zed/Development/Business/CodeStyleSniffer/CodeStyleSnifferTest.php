<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\CodeStyleSniffer;

use Codeception\Test\Unit;
use Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer;
use Spryker\Zed\Development\Business\Exception\CodeStyleSniffer\PathDoesNotExistException;

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
    protected $pathToBundles = 'vendor/spryker/spryker/Bundles/';

    /**
     * @return void
     */
    public function testCheckCodeStyleRunsCommandInProject()
    {
        $options = $this->getOptions(null);
        $pathToApplicationRoot = 'applicationRoot/';
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($pathToApplicationRoot, $options);

        $codeStyleSnifferMock->checkCodeStyle(null);
    }

    /**
     * @dataProvider allowedAllNames
     *
     * @param string $all
     * @param string $pathToBundles
     *
     * @return void
     */
    public function testCheckCodeStyleRunsCommandForAllBundlesInNonWhenBundleNameIsAll($all, $pathToBundles)
    {
        $options = $this->getOptions($all);
        $this->pathToBundles = $pathToBundles;
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($pathToBundles, $options);

        $codeStyleSnifferMock->checkCodeStyle($all);
    }

    /**
     * @return array
     */
    public function allowedAllNames()
    {
        return [
            ['all', 'vendor/spryker/spryker/Bundles/'],
            ['All', 'vendor/spryker/spryker/Bundles/'],
            ['all', 'vendor/spryker/'],
            ['All', 'vendor/spryker/'],
        ];
    }

    /**
     * @dataProvider allowedSplitNames
     *
     * @param string $bundle
     *
     * @return void
     */
    public function testCheckCodeStyleRunsCommandForBundleWithConvertedBundleNameInSplit($bundle)
    {
        $options = $this->getOptions($bundle);
        $this->pathToBundles = 'vendor/spryker/';
        $expectedPathToRunCommandWith = 'vendor/spryker/split-bundle/';
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($expectedPathToRunCommandWith, $options);

        $codeStyleSnifferMock->checkCodeStyle($bundle);
    }

    /**
     * @return array
     */
    public function allowedSplitNames()
    {
        return [
            ['SplitBundle'],
            ['splitBundle'],
            ['split-bundle'],
            ['Split-Bundle'],
        ];
    }

    /**
     * @dataProvider allowedNonSplitNames
     *
     * @param string $bundle
     *
     * @return void
     */
    public function testCheckCodeStyleRunsCommandForBundleWithConvertedBundleNameInNonSplit($bundle)
    {
        $options = $this->getOptions($bundle);
        $this->pathToBundles = 'vendor/spryker/spryker/Bundles/';
        $expectedPathToRunCommandWith = 'vendor/spryker/spryker/Bundles/NonSplitBundle/';
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($expectedPathToRunCommandWith, $options, false, false, true);

        $codeStyleSnifferMock->checkCodeStyle($bundle);
    }

    /**
     * @dataProvider allowedNonSplitNames
     *
     * @param string $bundle
     *
     * @return void
     */
    public function testCheckCodeStyleRunsCommandForPackageWithConvertedBundleNameInNonSplit($bundle)
    {
        $options = $this->getOptions($bundle);
        $this->pathToBundles = 'vendor/spryker/spryker/Bundles/';
        $expectedPathToRunCommandWith = 'vendor/spryker/non-split-bundle/';
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($expectedPathToRunCommandWith, $options, false, true);

        $codeStyleSnifferMock->checkCodeStyle($bundle);
    }

    /**
     * @return array
     */
    public function allowedNonSplitNames()
    {
        return [
            ['NonSplitBundle'],
            ['nonSplitBundle'],
            ['non_Split_Bundle'],
            ['non_split_bundle'],
        ];
    }

    /**
     * @return void
     */
    public function testCheckCodeStyleThrowsExceptionIfPathNotValid()
    {
        $bundle = 'not-existent-bundle-or-file';
        $options = $this->getOptions($bundle);
        $expectedPathToRunCommandWith = 'pathToBundles/not-existent-bundle-or-file/';
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($expectedPathToRunCommandWith, $options, false, false, false);

        $this->expectException(PathDoesNotExistException::class);
        $codeStyleSnifferMock->checkCodeStyle($bundle);
    }

    /**
     * @param string $bundle
     *
     * @return array
     */
    protected function getOptions($bundle)
    {
        return [
            'ignore' => $bundle ? '' : 'vendor/',
        ];
    }

    /**
     * @param string $expectedPathToRunCommandWith
     * @param array $options
     * @param bool $pathValidInSplit
     * @param bool $packagePathValidInNonSplit
     * @param bool $pathValidInNonSplit
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer
     */
    protected function getCodeStyleSnifferMock($expectedPathToRunCommandWith, array $options, $pathValidInSplit = true, $packagePathValidInNonSplit = false, $pathValidInNonSplit = false)
    {
        $codeStyleSnifferMockBuilder = $this->getMockBuilder(CodeStyleSniffer::class);
        $codeStyleSnifferMockBuilder->setConstructorArgs([
            'applicationRoot/',
            $this->pathToBundles,
            'coding standard',
        ]);
        $codeStyleSnifferMockBuilder->setMethods(['runSnifferCommand', 'isPathValid']);

        $codeStyleSnifferMock = $codeStyleSnifferMockBuilder->getMock();
        $codeStyleSnifferMock->method('runSnifferCommand')->with($expectedPathToRunCommandWith, $options);
        $codeStyleSnifferMock->method('isPathValid')->will($this->onConsecutiveCalls(
            $pathValidInSplit,
            $packagePathValidInNonSplit,
            $pathValidInNonSplit
        ));

        return $codeStyleSnifferMock;
    }
}
