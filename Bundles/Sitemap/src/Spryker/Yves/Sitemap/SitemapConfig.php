<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Sitemap;

use Spryker\Yves\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Sitemap\SitemapConfig getSharedConfig()
 */
class SitemapConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const SITEMAP_FILE_TIME_THRESHOLD = 86400;

    /**
     * Specification:
     * - Returns the file time threshold for the sitemap file.
     *
     * @api
     *
     * @return int
     */
    public function getSitemapFileTimeThreshold(): int
    {
        return static::SITEMAP_FILE_TIME_THRESHOLD;
    }

    /**
     * Specification:
     * - Returns the file path for the given store and file name.
     *
     * @api
     *
     * @param string $storeName
     * @param string $fileName
     *
     * @return string
     */
    public function getFilePath(string $storeName, string $fileName): string
    {
        return $this->getSharedConfig()->getFilePath($storeName, $fileName);
    }
}
