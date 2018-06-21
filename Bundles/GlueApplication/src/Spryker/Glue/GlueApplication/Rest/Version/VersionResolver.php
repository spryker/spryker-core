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

        $contentType = $request->headers->get(RequestConstantsInterface::HEADER_CONTENT_TYPE);
        if (!$contentType) {
            $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null;
        }

        if (!$contentType) {
            return $restVersionTransfer;
        }

        $headerParts = $this->contentTypeResolver->matchContentType($contentType);
        if (!isset($headerParts[2])) {
            return $restVersionTransfer;
        }

        $versionParts = explode('.', $headerParts[2]);

        $restVersionTransfer->setMajor((int)$versionParts[0])
            ->setMinor(isset($versionParts[1]) ? (int)$versionParts[1] : 0);

        return $restVersionTransfer;
    }
}
