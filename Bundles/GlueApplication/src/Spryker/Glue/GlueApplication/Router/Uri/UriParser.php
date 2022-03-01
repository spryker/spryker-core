<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Router\Uri;

class UriParser implements UriParserInterface
{
    /**
     * @param string $path
     *
     * @return array<mixed>|null
     */
    public function parse(string $path): ?array
    {
        $urlParts = $this->splitPath($path);
        if (count($urlParts) === 0) {
            return null;
        }

        return $this->extractResources($urlParts);
    }

    /**
     * @param array<string> $urlParts
     *
     * @return array<mixed>
     */
    protected function extractResources(array $urlParts): array
    {
        $resources = [];
        $index = 0;
        $urlPartsCount = count($urlParts);
        while ($index < $urlPartsCount) {
            $resources[] = [
                'type' => $urlParts[$index],
                'id' => $urlParts[$index + 1] ?? null,
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
