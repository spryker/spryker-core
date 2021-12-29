<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Version;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolver;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group Version
 * @group VersionResolverTest
 *
 * Add your own group annotations below this line
 */
class VersionResolverTest extends Unit
{
    /**
     * @return void
     */
    public function testFindVersionShouldReturnVersionTransferWhenVersionPresent(): void
    {
        $contentTypeResolverMock = $this->createContentTypeResolverMock();

        $contentTypeResolverMock
            ->method('matchContentType')
            ->willReturn([
                2 => '1.0',
            ]);

        $glueApplicationConfigMock = $this->getMockBuilder(GlueApplicationConfig::class)->getMock();

        $versionResolver = $this->createVersionResolver($contentTypeResolverMock, $glueApplicationConfigMock);

        $request = Request::create(
            '/',
            Request::METHOD_GET,
            [],
            [],
            [],
            [
                'HTTP_CONTENT-TYPE' => 'application/vnd.api+json; version=1.0',
            ],
        );

        $restVersionTransfer = $versionResolver->findVersion($request);

        $this->assertSame(1, $restVersionTransfer->getMajor());
        $this->assertSame(0, $restVersionTransfer->getMinor());
    }

    /**
     * @return void
     */
    public function testFindVersionShouldReturnEmptyTransferWhenContentTypeNotProvided(): void
    {
        $contentTypeResolverMock = $this->createContentTypeResolverMock();
        $glueApplicationConfigMock = $this->getMockBuilder(GlueApplicationConfig::class)->getMock();

        $versionResolver = $this->createVersionResolver($contentTypeResolverMock, $glueApplicationConfigMock);

        $request = Request::create('/', Request::METHOD_GET);

        $restVersionTransfer = $versionResolver->findVersion($request);

        $this->assertNull($restVersionTransfer->getMinor());
        $this->assertNull($restVersionTransfer->getMajor());
    }

    /**
     * @return void
     */
    public function testFindVersionShouldReturnEmptyTransferWhenContentTypeNotMatching(): void
    {
        $contentTypeResolverMock = $this->createContentTypeResolverMock();

        $contentTypeResolverMock
            ->method('matchContentType')
            ->willReturn([]);

        $glueApplicationConfigMock = $this->getMockBuilder(GlueApplicationConfig::class)->getMock();

        $versionResolver = $this->createVersionResolver($contentTypeResolverMock, $glueApplicationConfigMock);

        $request = Request::create(
            '/',
            Request::METHOD_GET,
            [],
            [],
            [],
            [
                'HTTP_CONTENT-TYPE' => 'application/vnd.api+json; version=1.0',
            ],
        );

        $restVersionTransfer = $versionResolver->findVersion($request);

        $this->assertNull($restVersionTransfer->getMinor());
        $this->assertNull($restVersionTransfer->getMajor());
    }

    /**
     * @return void
     */
    public function testGetUrlVersionShouldReturnFullVersionWhenVersionPresent(): void
    {
        $glueApplicationConfigMock = $this->getMockBuilder(GlueApplicationConfig::class)->getMock();
        $glueApplicationConfigMock->method('getPathVersionResolving')->willReturn(true);
        $glueApplicationConfigMock->method('getPathVersionPrefix')->willReturn('v');
        $glueApplicationConfigMock->method('getApiVersionResolvingRegex')->willReturn('/^(?P<fullVersion>(0|[1-9]\d*)(\.(0|[1-9]\d*))?)$/');

        $contentTypeResolverMock = $this->createContentTypeResolverMock();

        $versionResolver = new VersionResolver($contentTypeResolverMock, $glueApplicationConfigMock);

        $versionMatches = $versionResolver->getUrlVersionMatches('v1.2');

        $this->assertSame('1.2', $versionMatches['fullVersion']);
    }

    /**
     * @return void
     */
    public function testGetUrlVersionShouldNotReturnVersionTransferWhenWrongVersionPrefixPresent(): void
    {
        $glueApplicationConfigMock = $this->getMockBuilder(GlueApplicationConfig::class)->getMock();
        $glueApplicationConfigMock->method('getPathVersionResolving')->willReturn(true);
        $glueApplicationConfigMock->method('getPathVersionPrefix')->willReturn('v');
        $glueApplicationConfigMock->method('getApiVersionResolvingRegex')->willReturn('/^(?P<fullVersion>(0|[1-9]\d*)(\.(0|[1-9]\d*))?)$/');

        $contentTypeResolverMock = $this->createContentTypeResolverMock();

        $versionResolver = new VersionResolver($contentTypeResolverMock, $glueApplicationConfigMock);

        $versionMatches = $versionResolver->getUrlVersionMatches('version1.2');

        $this->assertEmpty($versionMatches);
    }

    /**
     * @return void
     */
    public function testGetUrlVersionShouldNotReturnVersionTransferWhenNoVersionGiven(): void
    {
        $glueApplicationConfigMock = $this->getMockBuilder(GlueApplicationConfig::class)->getMock();
        $glueApplicationConfigMock->method('getPathVersionResolving')->willReturn(true);
        $glueApplicationConfigMock->method('getPathVersionPrefix')->willReturn('v');
        $glueApplicationConfigMock->method('getApiVersionResolvingRegex')->willReturn('/^(?P<fullVersion>(0|[1-9]\d*)(\.(0|[1-9]\d*))?)$/');

        $contentTypeResolverMock = $this->createContentTypeResolverMock();

        $versionResolver = new VersionResolver($contentTypeResolverMock, $glueApplicationConfigMock);

        $versionMatches = $versionResolver->getUrlVersionMatches('');

        $this->assertEmpty($versionMatches);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface $contentTypeResolverMock
     * @param \Spryker\Glue\GlueApplication\GlueApplicationConfig $glueApplicationConfigMock
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface
     */
    protected function createVersionResolver(
        ContentTypeResolverInterface $contentTypeResolverMock,
        GlueApplicationConfig $glueApplicationConfigMock
    ): VersionResolverInterface {
        return new VersionResolver($contentTypeResolverMock, $glueApplicationConfigMock);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface
     */
    protected function createContentTypeResolverMock(): ContentTypeResolverInterface
    {
        $contentTypeResolverMock = $this->getMockBuilder(ContentTypeResolverInterface::class)
            ->setMethods(['matchContentType', 'addResponseHeaders'])
            ->getMock();

        return $contentTypeResolverMock;
    }
}
