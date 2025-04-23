<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Version;

use Generated\Shared\Transfer\RestVersionTransfer;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Symfony\Component\HttpFoundation\Request;

class VersionResolver implements VersionResolverInterface
{
    /**
     * @var int
     */
    public const PART_VERSION_NUMBER = 2;

    /**
     * @var int
     */
    public const PART_VERSION_MINOR = 1;

    /**
     * @var int
     */
    public const PART_VERSION_MAJOR = 0;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface
     */
    protected $contentTypeResolver;

    /**
     * @var \Spryker\Glue\GlueApplication\GlueApplicationConfig
     */
    protected $config;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface $contentTypeResolver
     * @param \Spryker\Glue\GlueApplication\GlueApplicationConfig $config
     */
    public function __construct(ContentTypeResolverInterface $contentTypeResolver, GlueApplicationConfig $config)
    {
        $this->contentTypeResolver = $contentTypeResolver;
        $this->config = $config;
    }

    /**
     * @param string $urlString
     *
     * @return array
     */
    public function getUrlVersionMatches(string $urlString): array
    {
        if (!$this->config->getPathVersionResolving()) {
            return [];
        }

        if ($this->config->getPathVersionPrefix()) {
            if (strpos($urlString, $this->config->getPathVersionPrefix()) !== 0) {
                return [];
            }

            $fullVersion = substr($urlString, strlen($this->config->getPathVersionPrefix()));
            preg_match($this->config->getApiVersionResolvingRegex(), $fullVersion, $matches);

            return $matches ?: [];
        }

        preg_match($this->config->getApiVersionResolvingRegex(), $urlString, $matches);

        return $matches ?: [];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestVersionTransfer
     */
    public function findVersion(Request $request): RestVersionTransfer
    {
        $restVersionTransfer = new RestVersionTransfer();

        if ($this->config->getPathVersionResolving()) {
            $urlParts = explode('/', trim($request->getPathInfo(), '\/'));
            $matches = $this->getUrlVersionMatches($urlParts[0]);
            if ($matches) {
                if (isset($matches['fullVersion'])) {
                    return $this->extractVersionTransfer($matches['fullVersion'], $restVersionTransfer);
                }

                if (isset($matches[1])) {
                    return $this->extractVersionTransfer($matches[1], $restVersionTransfer);
                }
            }

            return $restVersionTransfer;
        }

        $contentType = (string)$request->headers->get(RequestConstantsInterface::HEADER_CONTENT_TYPE);
        if (!$contentType) {
            return $restVersionTransfer;
        }

        $headerParts = $this->contentTypeResolver->matchContentType($contentType);
        if (!isset($headerParts[static::PART_VERSION_NUMBER])) {
            return $restVersionTransfer;
        }

        return $this->extractVersionTransfer($headerParts[static::PART_VERSION_NUMBER], $restVersionTransfer);
    }

    /**
     * @param string $versionString
     * @param \Generated\Shared\Transfer\RestVersionTransfer $restVersionTransfer
     *
     * @return \Generated\Shared\Transfer\RestVersionTransfer
     */
    protected function extractVersionTransfer(string $versionString, RestVersionTransfer $restVersionTransfer): RestVersionTransfer
    {
        $versionParts = explode('.', $versionString);

        $restVersionTransfer->setMajor((int)$versionParts[static::PART_VERSION_MAJOR]);

        if (isset($versionParts[static::PART_VERSION_MINOR])) {
            $restVersionTransfer->setMinor((int)$versionParts[static::PART_VERSION_MINOR]);
        }

        return $restVersionTransfer;
    }
}
