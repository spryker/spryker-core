<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SitemapExtension\Dependency\Plugin;

/**
 * Implement this plugin interface to provide additional URLs for the sitemap.
 */
interface SitemapDataProviderPluginInterface
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
     * - Returns an array of URL related data to be included in the sitemap.
     *
     * @api
     *
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
     */
    public function getSitemapUrls(string $storeName): array;
}
