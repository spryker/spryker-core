<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Request;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestVersionTransfer;
use Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface;
use Spryker\Glue\GlueApplication\Rest\Language\LanguageNegotiationInterface;
use Spryker\Glue\GlueApplication\Rest\Request\RequestMetaDataExtractor;
use Spryker\Glue\GlueApplication\Rest\Request\RequestMetaDataExtractorInterface;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group Request
 * @group RequestMetadataExtractorTest
 *
 * Add your own group annotations below this line
 */
class RequestMetadataExtractorTest extends Unit
{
    /**
     * @return void
     */
    public function testExtractShouldProvideMetadataFromRequest(): void
    {
        $versionResolverMock = $this->createVersionResolverMock();

        $versionResolverMock->method('findVersion')
            ->willReturn(
                (new RestVersionTransfer())
                    ->setMajor(1)
                    ->setMinor(1)
            );

        $contentTypeResolverMock = $this->createContentTypeResolverMock();

        $contentTypeResolverMock->method('matchContentType')
            ->willReturn([
                '',
                'json',
            ]);

        $languageNegotiationMock = $this->createLanguageNegotiationMock();

        $languageNegotiationMock
            ->method('getLanguageIsoCode')
            ->willReturn('de_DE');

        $requestMetadataExtractor = $this->createMetadataExtractor(
            $versionResolverMock,
            $contentTypeResolverMock,
            $languageNegotiationMock
        );

        $request = Request::create(
            '/',
            Request::METHOD_GET,
            [],
            [],
            [],
            [
                'HTTP_CONTENT-TYPE' => 'application/vnd.api+json; version=1.0',
                'HTTP_ACCEPT' => 'application/vnd.api+json; version=1.0',
            ]
        );

        $metadata = $requestMetadataExtractor->extract($request);

        $this->assertEquals('json', $metadata->getAcceptFormat());
        $this->assertEquals('json', $metadata->getContentTypeFormat());
        $this->assertFalse($metadata->isProtected());
        $this->assertEquals('de_DE', $metadata->getLocale());
        $this->assertEquals(Request::METHOD_GET, $metadata->getMethod());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface $versionResolverMock
     * @param \Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface $contentTypeResolverMock
     * @param \Spryker\Glue\GlueApplication\Rest\Language\LanguageNegotiationInterface $languageNegotiationMock
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestMetaDataExtractorInterface
     */
    protected function createMetadataExtractor(
        VersionResolverInterface $versionResolverMock,
        ContentTypeResolverInterface $contentTypeResolverMock,
        LanguageNegotiationInterface $languageNegotiationMock
    ): RequestMetaDataExtractorInterface {
        return new RequestMetaDataExtractor($versionResolverMock, $contentTypeResolverMock, $languageNegotiationMock);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface
     */
    protected function createVersionResolverMock(): VersionResolverInterface
    {
        return $this->getMockBuilder(VersionResolverInterface::class)
            ->setMethods(['findVersion'])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface
     */
    protected function createContentTypeResolverMock(): ContentTypeResolverInterface
    {
        return $this->getMockBuilder(ContentTypeResolverInterface::class)
            ->setMethods(['matchContentType', 'addResponseHeaders'])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Language\LanguageNegotiationInterface
     */
    protected function createLanguageNegotiationMock(): LanguageNegotiationInterface
    {
        return $this->getMockBuilder(LanguageNegotiationInterface::class)
            ->setMethods(['getLanguageIsoCode'])
            ->getMock();
    }
}
