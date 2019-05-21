<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface;
use Spryker\Glue\GlueApplication\Rest\Language\LanguageNegotiationInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\Metadata;
use Spryker\Glue\GlueApplication\Rest\Request\Data\Version;
use Spryker\Glue\GlueApplication\Rest\Request\Data\VersionInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\GlueApplication\Rest\Serialize\EncoderMatcher;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestMetaDataExtractor implements RequestMetaDataExtractorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface
     */
    protected $versionExtractor;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface
     */
    protected $contentTypeResolver;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Language\LanguageNegotiationInterface
     */
    protected $languageNegotiation;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface $versionExtractor
     * @param \Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface $contentTypeResolver
     * @param \Spryker\Glue\GlueApplication\Rest\Language\LanguageNegotiationInterface $languageNegotiation
     */
    public function __construct(
        VersionResolverInterface $versionExtractor,
        ContentTypeResolverInterface $contentTypeResolver,
        LanguageNegotiationInterface $languageNegotiation
    ) {
        $this->versionExtractor = $versionExtractor;
        $this->contentTypeResolver = $contentTypeResolver;
        $this->languageNegotiation = $languageNegotiation;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\Metadata
     */
    public function extract(Request $request): Metadata
    {
        return new Metadata(
            $this->findAcceptFormat($request),
            $this->findContentTypeFormat($request),
            $request->getMethod(),
            $this->getLocale($request),
            $request->attributes->get(RequestConstantsInterface::ATTRIBUTE_IS_PROTECTED, false),
            $this->createVersion($request)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function findContentTypeFormat(Request $request): string
    {
        $contentType = (string)$request->headers->get(RequestConstantsInterface::HEADER_CONTENT_TYPE);
        $headerParts = $this->contentTypeResolver->matchContentType($contentType);

        if (count($headerParts) < 2) {
            return EncoderMatcher::DEFAULT_FORMAT;
        }

        return $headerParts[1];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    public function findAcceptFormat(Request $request): string
    {
        $accept = (string)$request->headers->get(RequestConstantsInterface::HEADER_ACCEPT);

        $headerParts = $this->contentTypeResolver->matchContentType($accept);

        if (count($headerParts) === 0) {
            return EncoderMatcher::DEFAULT_FORMAT;
        }

        return $headerParts[1];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function getLocale(Request $request): string
    {
        $acceptLanguage = (string)$request->headers->get(RequestConstantsInterface::HEADER_ACCEPT_LANGUAGE, '');

        return $this->languageNegotiation->getLanguageIsoCode($acceptLanguage);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\VersionInterface|null
     */
    protected function createVersion(Request $request): ?VersionInterface
    {
        $versionTransfer = $request->attributes->get(RequestConstantsInterface::ATTRIBUTE_RESOURCE_VERSION, '');

        if (!$versionTransfer) {
            return null;
        }

        if (!$versionTransfer->getMajor()) {
            return null;
        }

        return new Version(
            $versionTransfer->getMajor(),
            $versionTransfer->getMinor()
        );
    }
}
