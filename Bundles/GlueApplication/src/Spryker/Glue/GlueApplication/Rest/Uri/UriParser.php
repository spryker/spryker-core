<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Uri;

use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Symfony\Component\HttpFoundation\Request;

class UriParser implements UriParserInterface
{
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
                RequestConstantsInterface::ATTRIBUTE_ID => isset($urlParts[$index + 1]) ? $urlParts[$index + 1] : null,
            ];

            $index += 2;
        }

        return $resources;
    }

    /**
     * @param string $path
     *
     * @return string[]
     */
    protected function splitPath($path): array
    {
        return explode('/', trim($path, '\/'));
    }
}
