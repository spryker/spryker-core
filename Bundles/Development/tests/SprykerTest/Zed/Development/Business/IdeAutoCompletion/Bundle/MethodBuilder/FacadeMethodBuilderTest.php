<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\FacadeMethodBuilder;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\NamespaceExtractor;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group IdeAutoCompletion
 * @group Bundle
 * @group MethodBuilder
 * @group Facade
 * @group FacadeMethodBuilderTest
 * Add your own group annotations below this line
 */
class FacadeMethodBuilderTest extends Unit
{
    public const BASE_DIRECTORY = '/foo/bar/baz/*/src/';
    public const BUNDLE_DIRECTORY = '/foo/bar/baz/FooBundle/src/Spryker/FooApplication/';

    /**
     * @return void
     */
    public function testMethodNameIsFacade()
    {
        $methodBuilderMock = $this->getFacadeMethodBuilderMock();
        $methodBuilderMock
            ->expects($this->any())
            ->method('findFileByName')
            ->willReturn(new SplFileInfo(static::BUNDLE_DIRECTORY . 'FooBundle/Business/FooBundleFacadeInterface.php', 'foo', 'bar'));

        $bundleMethodTransfer = $methodBuilderMock->getMethod($this->getBundleTransfer());

        $this->assertSame('facade', $bundleMethodTransfer->getName());
    }

    /**
     * @return void
     */
    public function testFileLookupIsPerformedInBusinessLayer()
    {
        $methodBuilderMock = $this->getFacadeMethodBuilderMock();
        $methodBuilderMock
            ->expects($this->any())
            ->method('findFileByName')
            ->with($this->anything(), $this->equalTo(static::BUNDLE_DIRECTORY . 'FooBundle/Business/'));

        $methodBuilderMock->getMethod($this->getBundleTransfer());
    }

    /**
     * @return void
     */
    public function testFileLookupPrefersInterface()
    {
        $methodBuilderMock = $this->getFacadeMethodBuilderMock();
        $methodBuilderMock
            ->expects($this->exactly(1))
            ->method('findFileByName')
            ->withConsecutive(
                [$this->equalTo('FooBundleFacadeInterface.php'), $this->anything()]
            )
            ->will($this->onConsecutiveCalls(
                new SplFileInfo(static::BUNDLE_DIRECTORY . 'FooBundle/Business/FooBundleFacadeInterface.php', 'foo', 'bar'),
                new SplFileInfo(static::BUNDLE_DIRECTORY . 'FooBundle/Business/FooBundleFacade.php', 'foo', 'bar')
            ));

        $bundleMethodTransfer = $methodBuilderMock->getMethod($this->getBundleTransfer());

        $this->assertSame('FooBundleFacadeInterface', $bundleMethodTransfer->getClassName());
    }

    /**
     * @return void
     */
    public function testFileLookupFallsBackToConcreteClassIfInterfaceIsMissing()
    {
        $methodBuilderMock = $this->getFacadeMethodBuilderMock();
        $methodBuilderMock
            ->expects($this->exactly(2))
            ->method('findFileByName')
            ->withConsecutive(
                [$this->equalTo('FooBundleFacadeInterface.php'), $this->anything()],
                [$this->equalTo('FooBundleFacade.php'), $this->anything()]
            )
            ->will($this->onConsecutiveCalls(
                null,
                new SplFileInfo(static::BUNDLE_DIRECTORY . 'FooBundle/Business/FooBundleFacade.php', 'foo', 'bar')
            ));

        $bundleMethodTransfer = $methodBuilderMock->getMethod($this->getBundleTransfer());

        $this->assertSame('FooBundleFacade', $bundleMethodTransfer->getClassName());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\ClientMethodBuilder
     */
    protected function getFacadeMethodBuilderMock()
    {
        $methodBuilderMock = $this
            ->getMockBuilder(FacadeMethodBuilder::class)
            ->setConstructorArgs([$this->getNamespaceExtractorMock()])
            ->setMethods(['findFileByName', 'isSearchDirectoryAccessible'])
            ->getMock();

        $methodBuilderMock
            ->expects($this->any())
            ->method('isSearchDirectoryAccessible')
            ->willReturn(true);

        return $methodBuilderMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\NamespaceExtractorInterface
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
        $bundleTransfer->setNamespaceName('Generated\FooApplication\Ide');
        $bundleTransfer->setBaseDirectory(static::BASE_DIRECTORY);
        $bundleTransfer->setDirectory(static::BUNDLE_DIRECTORY);
        $bundleTransfer->setMethodName('fooBundle');

        return $bundleTransfer;
    }
}
