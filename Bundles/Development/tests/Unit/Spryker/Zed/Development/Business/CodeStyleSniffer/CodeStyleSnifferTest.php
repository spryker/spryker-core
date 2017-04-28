<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Development\Business\CodeStyleSniffer;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer;
use Spryker\Zed\Development\Business\Exception\CodeStyleSniffer\PathDoesNotExistException;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Development
 * @group Business
 * @group CodeStyleSniffer
 * @group CodeStyleSnifferTest
 */
class CodeStyleSnifferTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider allowedAllNames
     *
     * @param string $all
     *
     * @return void
     */
    public function testCheckCodeStyleRunsCommandForAllBundlesWhenBundleNameIsAll($all)
    {
        $options = $this->getOptions($all);
        $expectedPathToRunCommandWith = 'pathToBundles/';
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($expectedPathToRunCommandWith, $options);

        $codeStyleSnifferMock->checkCodeStyle($all);
    }

    /**
     * @return array
     */
    public function allowedAllNames()
    {
        return [
            ['all'],
            ['All'],
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
        $expectedPathToRunCommandWith = 'pathToBundles/NonSplitBundle/';
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($expectedPathToRunCommandWith, $options);

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
     * @dataProvider allowedSplitNames
     *
     * @param string $bundle
     *
     * @return void
     */
    public function testCheckCodeStyleRunsCommandForBundleWithConvertedBundleNameInSplit($bundle)
    {
        $options = $this->getOptions($bundle);
        $expectedPathToRunCommandWith = 'pathToBundles/split-bundle/';
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($expectedPathToRunCommandWith, $options, false);

        $codeStyleSnifferMock->checkCodeStyle($bundle);
    }

    /**
     * @return array
     */
    public function allowedSplitNames()
    {
        return [
            ['split-bundle'],
            ['Split-Bundle'],
            ['SplitBundle'],
            ['splitBundle'],
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
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($expectedPathToRunCommandWith, $options, false, false);

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
     * @param bool $pathValidInNonSplit
     * @param bool $pathValidInSplit
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer
     */
    protected function getCodeStyleSnifferMock($expectedPathToRunCommandWith, array $options, $pathValidInNonSplit = true, $pathValidInSplit = true)
    {
        $codeStyleSnifferMockBuilder = $this->getMockBuilder(CodeStyleSniffer::class);
        $codeStyleSnifferMockBuilder->setConstructorArgs([
            'applicationRoot/',
            'pathToBundles/',
            'coding standard standard',
        ]);
        $codeStyleSnifferMockBuilder->setMethods(['runSnifferCommand', 'isPathValid']);

        $codeStyleSnifferMock = $codeStyleSnifferMockBuilder->getMock();
        $codeStyleSnifferMock->method('runSnifferCommand')->with($expectedPathToRunCommandWith, $options);
        $codeStyleSnifferMock->method('isPathValid')->will($this->onConsecutiveCalls($pathValidInNonSplit, $pathValidInSplit));

        return $codeStyleSnifferMock;
    }

}
