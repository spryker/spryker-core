<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\FacadeMethodBuilder;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\NamespaceExtractor;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Development
 * @group Business
 * @group IdeAutoCompletion
 * @group Bundle
 * @group MethodBuilder
 * @group FacadeMethodBuilderTest
 */
class FacadeMethodBuilderTest extends Test
{

    const BASE_DIRECTORY = '/foo/bar/baz/*/src/';
    const BUNDLE_DIRECTORY = '/foo/bar/baz/FooBundle/src/Spryker/FooApplication/';

    public function testMethodNameIsFacade()
    {
        $finderMock = $this->getFinderMock();
        $finderMock
            ->expects($this->any())
            ->method('name')
            ->willReturn([new SplFileInfo(static::BUNDLE_DIRECTORY . 'FooBundle/Business/FooBundleFacadeInterface.php', null, null)]);

        $methodBuilder = new FacadeMethodBuilder($finderMock, $this->getNamespaceExtractorMock());
        $bundleMethodTransfer = $methodBuilder->getMethod($this->getBundleTransfer());

        $this->assertSame('facade', $bundleMethodTransfer->getName());
    }

    public function testFileLookupIsPerformedInBusinessLayer()
    {
        $finderMock = $this->getFinderMock();
        $finderMock
            ->expects($this->any())
            ->method('in')
            ->with($this->equalTo(static::BUNDLE_DIRECTORY . '/*/Business/'));
        $finderMock
            ->expects($this->any())
            ->method('name')
            ->willReturn([]);

        $methodBuilder = new FacadeMethodBuilder($finderMock, $this->getNamespaceExtractorMock());
        $methodBuilder->getMethod($this->getBundleTransfer());
    }

    public function testFileLookupPrefersInterface()
    {
        $finderMock = $this->getFinderMock();
        $finderMock
            ->expects($this->exactly(1))
            ->method('name')
            ->withConsecutive(
                ['FooBundleFacadeInterface.php'],
                ['FooBundleFacade.php']
            )
            ->will($this->onConsecutiveCalls(
                [new SplFileInfo(static::BUNDLE_DIRECTORY . 'FooBundle/Business/FooBundleFacadeInterface.php', null, null)],
                [new SplFileInfo(static::BUNDLE_DIRECTORY . 'FooBundle/Business/FooBundleFacade.php', null, null)]
            ));

        $methodBuilder = new FacadeMethodBuilder($finderMock, $this->getNamespaceExtractorMock());
        $bundleMethodTransfer = $methodBuilder->getMethod($this->getBundleTransfer());

        $this->assertSame('FooBundleFacadeInterface', $bundleMethodTransfer->getClassName());
    }

    public function testFileLookupFallsBackToConcreteClassIfInterfaceIsMissing()
    {
        $finderMock = $this->getFinderMock();
        $finderMock
            ->expects($this->exactly(2))
            ->method('name')
            ->withConsecutive(
                ['FooBundleFacadeInterface.php'],
                ['FooBundleFacade.php']
            )
            ->will($this->onConsecutiveCalls(
                [],
                [new SplFileInfo(static::BUNDLE_DIRECTORY . 'FooBundle/Business/FooBundleFacade.php', null, null)]
            ));

        $methodBuilder = new FacadeMethodBuilder($finderMock, $this->getNamespaceExtractorMock());
        $bundleMethodTransfer = $methodBuilder->getMethod($this->getBundleTransfer());

        $this->assertSame('FooBundleFacade', $bundleMethodTransfer->getClassName());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Finder\Finder
     */
    protected function getFinderMock()
    {
        $mock = $this
            ->getMockBuilder(Finder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('files')
            ->willReturnSelf();

        $mock
            ->expects($this->any())
            ->method('in')
            ->willReturnSelf();

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\NamespaceExtractorInterface
     */
    protected function getNamespaceExtractorMock()
    {
        return $this
            ->getMockBuilder(NamespaceExtractor::class)
            ->getMock();
    }

    /**
     * @return \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer
     */
    protected function getBundleTransfer()
    {
        $bundleTransfer = new IdeAutoCompletionBundleTransfer();
        $bundleTransfer->setName('FooBundle');
        $bundleTransfer->setNamespace('Generated\FooApplication\Ide');
        $bundleTransfer->setBaseDirectory(static::BASE_DIRECTORY);
        $bundleTransfer->setDirectory(static::BUNDLE_DIRECTORY);
        $bundleTransfer->setMethodName('fooBundle');

        return $bundleTransfer;
    }

}
