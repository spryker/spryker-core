<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Uri;

use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface;
use Symfony\Component\HttpFoundation\Request;

class UriParser implements UriParserInterface
{
    protected VersionResolverInterface $versionResolver;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface $versionResolver
     */
    public function __construct(VersionResolverInterface $versionResolver)
    {
        $this->versionResolver = $versionResolver;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|null
     */
    public function parse(Request $request): ?array
    {
        $urlParts = $this->splitPath($request->getPathInfo());
        if (count($urlParts) === 0) {
            return null;
        }

        if ($this->versionResolver->getUrlVersionMatches($urlParts[0])) {
            array_shift($urlParts);
        }

        return $this->extractResources($urlParts);
    }

    /**
     * @param array $urlParts
     *
     * @return array
     */
    protected function extractResources(array $urlParts): array
    {
        $resources = [];
        $index = 0;
        $urlPartsCount = count($urlParts);
        while ($index < $urlPartsCount) {
            $resources[] = [
                RequestConstantsInterface::ATTRIBUTE_TYPE => $urlParts[$index],
                RequestConstantsInterface::ATTRIBUTE_ID => $urlParts[$index + 1] ?? null,
            ];

            $index += 2;
        }

        return $resources;
    }

    /**
     * @param string $path
     *
     * @return array<string>
     */
    protected function splitPath(string $path): array
    {
        return explode('/', trim($path, '\/'));
    }
}
