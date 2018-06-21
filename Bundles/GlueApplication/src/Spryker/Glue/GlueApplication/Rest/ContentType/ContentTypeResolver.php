<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\ContentType;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\VersionInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Symfony\Component\HttpFoundation\Response;

class ContentTypeResolver implements ContentTypeResolverInterface
{
    protected const CONTENT_TYPE_REGULAR_EXPRESION = '/application\/vnd.api\+([a-z]+)(?:;\s(?:version=([\d]+\.?[\d]+)))?/i';
    protected const RESPONSE_CONTENT_TYPE = 'application/vnd.api+%s';

    /**
     * @param string $contentType
     *
     * @return array
     */
    public function matchContentType(string $contentType): array
    {
        $contentTypeParts = [];
        preg_match(static::CONTENT_TYPE_REGULAR_EXPRESION, $contentType, $contentTypeParts);

        return $contentTypeParts;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Symfony\Component\HttpFoundation\Response $httpResponse
     *
     * @return void
     */
    public function addResponseHeaders(RestRequestInterface $restRequest, Response $httpResponse): void
    {
        $contentType = sprintf(
            static::RESPONSE_CONTENT_TYPE,
            $restRequest->getMetadata()->getContentTypeFormat()
        );

        $version = $restRequest->getMetadata()->getVersion();
        if ($version) {
            $contentType = $this->addVersion($version, $contentType);
        }

        $httpResponse->headers->set(RequestConstantsInterface::HEADER_CONTENT_TYPE, $contentType);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\VersionInterface $version
     * @param string $contentType
     *
     * @return string
     */
    protected function addVersion(VersionInterface $version, string $contentType): string
    {
        $contentType .= '; version=' . $version->getMajor() . '.' . $version->getMinor();

        return $contentType;
    }
}
