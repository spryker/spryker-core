<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Version;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolver;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
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

        $versionResolver = $this->createVersionResolver($contentTypeResolverMock);

        $request = Request::create(
            '/',
            Request::METHOD_GET,
            [],
            [],
            [],
            [
                'HTTP_CONTENT-TYPE' => 'application/vnd.api+json; version=1.0',
            ]
        );

        $restVersionTransfer = $versionResolver->findVersion($request);

        $this->assertEquals(1, $restVersionTransfer->getMajor());
        $this->assertEquals(0, $restVersionTransfer->getMinor());
    }

    /**
     * @return void
     */
    public function testFindVersionShouldReturnEmptyTransferWhenContentTypeNotProvided(): void
    {
        $contentTypeResolverMock = $this->createContentTypeResolverMock();

        $versionResolver = $this->createVersionResolver($contentTypeResolverMock);

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

        $versionResolver = $this->createVersionResolver($contentTypeResolverMock);

        $request = Request::create(
            '/',
            Request::METHOD_GET,
            [],
            [],
            [],
            [
                'HTTP_CONTENT-TYPE' => 'application/vnd.api+json; version=1.0',
            ]
        );

        $restVersionTransfer = $versionResolver->findVersion($request);

        $this->assertNull($restVersionTransfer->getMinor());
        $this->assertNull($restVersionTransfer->getMajor());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface $contentTypeResolverMock
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface
     */
    protected function createVersionResolver(
        ContentTypeResolverInterface $contentTypeResolverMock
    ): VersionResolverInterface {

        return new VersionResolver($contentTypeResolverMock);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface
     */
    protected function createContentTypeResolverMock()
    {
        $contentTypeResolverMock = $this->getMockBuilder(ContentTypeResolverInterface::class)
            ->setMethods(['matchContentType', 'addResponseHeaders'])
            ->getMock();

        return $contentTypeResolverMock;
    }
}
