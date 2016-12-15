<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\QueryContainerMethodBuilder;
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
 * @group QueryContainerMethodBuilderTest
 */
class QueryContainerMethodBuilderTest extends Test
{

    const BASE_DIRECTORY = '/foo/bar/baz/*/src/';
    const BUNDLE_DIRECTORY = '/foo/bar/baz/FooBundle/src/Spryker/FooApplication/';

    public function testMethodNameIsQueryConntainer()
    {
        $finderMock = $this->getFinderMock();
        $finderMock
            ->expects($this->any())
            ->method('name')
            ->willReturn([new SplFileInfo(static::BUNDLE_DIRECTORY . 'FooBundle/Persistence/FooBundleQueryContainerInterface.php', null, null)]);

        $methodBuilder = new QueryContainerMethodBuilder($finderMock, $this->getNamespaceExtractorMock());
        $bundleMethodTransfer = $methodBuilder->getMethod($this->getBundleTransfer());

        $this->assertSame('queryContainer', $bundleMethodTransfer->getName());
    }

    public function testFileLookupIsPerformedInPersistenceLayer()
    {
        $finderMock = $this->getFinderMock();
        $finderMock
            ->expects($this->any())
            ->method('in')
            ->with($this->equalTo(static::BUNDLE_DIRECTORY . '/*/Persistence/'));
        $finderMock
            ->expects($this->any())
            ->method('name')
            ->willReturn([]);

        $methodBuilder = new QueryContainerMethodBuilder($finderMock, $this->getNamespaceExtractorMock());
        $methodBuilder->getMethod($this->getBundleTransfer());
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
