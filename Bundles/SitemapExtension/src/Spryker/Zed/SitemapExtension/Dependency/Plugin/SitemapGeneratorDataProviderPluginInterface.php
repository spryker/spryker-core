<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SitemapExtension\Dependency\Plugin;

use Generator;

interface SitemapGeneratorDataProviderPluginInterface
{
    /**
     * Specification:
     * - Returns the type of the entity for which the sitemap URLs are provided.
     * - This type is used to group the URLs in the sitemap.
     *
     * @api
     *
     * @return string
     */
    public function getEntityType(): string;

    /**
     * Specification:
     * - Returns a generator of arrays of URL related data to be included in the sitemap.
     * - It has to return an empty array as the last element to demonstrate the end of the generator.
     *
     * @api
     *
     * @param string $storeName
     * @param int $chunkSizePerIteration
     *
     * @return \Generator
     */
    public function getSitemapUrls(string $storeName, int $chunkSizePerIteration): Generator;
}
