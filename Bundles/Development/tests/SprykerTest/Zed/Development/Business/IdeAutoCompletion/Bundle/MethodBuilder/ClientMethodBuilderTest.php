<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\ClientMethodBuilder;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\NamespaceExtractor;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group IdeAutoCompletion
 * @group Bundle
 * @group MethodBuilder
 * @group ClientMethodBuilderTest
 * Add your own group annotations below this line
 */
class ClientMethodBuilderTest extends Unit
{
    public const BASE_DIRECTORY = '/foo/bar/baz/*/src/';
    public const BUNDLE_DIRECTORY = '/foo/bar/baz/FooBundle/src/Spryker/Client/';

    /**
     * @return void
     */
    public function testMethodNameIsClient()
    {
        $methodBuilderMock = $this->getClientMethodBuilderMock();
        $methodBuilderMock
            ->expects($this->any())
            ->method('findFileByName')
            ->willReturn(new SplFileInfo(static::BUNDLE_DIRECTORY . 'FooBundle/FooBundleClientInterface.php', 'foo', 'bar'));

        $bundleMethodTransfer = $methodBuilderMock->getMethod($this->getBundleTransfer());

        $this->assertSame('client', $bundleMethodTransfer->getName());
    }

    /**
     * @return void
     */
    public function testFileLookupIsPerformedInClientApplication()
    {
        $methodBuilderMock = $this->getClientMethodBuilderMock();
        $methodBuilderMock
            ->expects($this->any())
            ->method('findFileByName')
            ->with($this->anything(), $this->equalTo(static::BUNDLE_DIRECTORY . 'FooBundle/'));

        $methodBuilderMock->getMethod($this->getBundleTransfer());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\ClientMethodBuilder
     */
    protected function getClientMethodBuilderMock()
    {
        $methodBuilderMock = $this
            ->getMockBuilder(ClientMethodBuilder::class)
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
