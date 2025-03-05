<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sitemap\Business;

interface SitemapFacadeInterface
{
    /**
     * Specification:
     * - Generates sitemap XML files.
     * - Executes {@link \Spryker\Zed\SitemapExtension\Dependency\Plugin\SitemapDataProviderPluginInterface} plugin stack.
     *
     * @api
     *
     * @return void
     */
    public function generateSitemapFiles(): void;
}
