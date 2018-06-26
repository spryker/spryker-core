<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Version;

use Generated\Shared\Transfer\RestVersionTransfer;
use Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Symfony\Component\HttpFoundation\Request;

class VersionResolver implements VersionResolverInterface
{
    public const PART_VERSION_NUMBER = 2;
    public const PART_VERSION_MINOR = 1;
    public const PART_VERSION_MAJOR = 0;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface
     */
    protected $contentTypeResolver;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface $contentTypeResolver
     */
    public function __construct(ContentTypeResolverInterface $contentTypeResolver)
    {
        $this->contentTypeResolver = $contentTypeResolver;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestVersionTransfer
     */
    public function findVersion(Request $request): RestVersionTransfer
    {
        $restVersionTransfer = new RestVersionTransfer();

        $contentType = (string)$request->headers->get(RequestConstantsInterface::HEADER_CONTENT_TYPE);

        if (!$contentType) {
            return $restVersionTransfer;
        }

        $headerParts = $this->contentTypeResolver->matchContentType($contentType);
        if (!isset($headerParts[static::PART_VERSION_NUMBER])) {
            return $restVersionTransfer;
        }

        $versionParts = explode('.', $headerParts[static::PART_VERSION_NUMBER]);

        $restVersionTransfer->setMajor((int)$versionParts[static::PART_VERSION_MAJOR])
            ->setMinor(isset($versionParts[static::PART_VERSION_MINOR]) ? (int)$versionParts[static::PART_VERSION_MINOR] : 0);

        return $restVersionTransfer;
    }
}
