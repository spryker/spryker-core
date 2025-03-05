<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Sitemap\Reader\Cache;

use DateTime;
use Spryker\Shared\Sitemap\SitemapConstants;
use Spryker\Yves\Sitemap\Dependency\Service\SitemapToFlysystemServiceInterface;
use Spryker\Yves\Sitemap\Reader\SitemapReaderInterface;
use Spryker\Yves\Sitemap\SitemapConfig;

class SitemapCacheReader implements SitemapReaderInterface
{
    /**
     * @param \Spryker\Yves\Sitemap\Dependency\Service\SitemapToFlysystemServiceInterface $flysystemService
     * @param \Spryker\Yves\Sitemap\SitemapConfig $config
     */
    public function __construct(
        protected SitemapToFlysystemServiceInterface $flysystemService,
        protected SitemapConfig $config
    ) {
    }

    /**
     * @param string $sitemapFileName
     * @param string $storeName
     *
     * @return mixed
     */
    public function readStream(string $sitemapFileName, string $storeName): mixed
    {
        $sitemapFilePath = $this->config->getFilePath($storeName, $sitemapFileName);

        if ($this->flysystemService->has(SitemapConstants::FILESYSTEM_NAME_CACHE, $sitemapFilePath) === false) {
            return null;
        }

        $timestamp = $this->flysystemService->getTimestamp(SitemapConstants::FILESYSTEM_NAME_CACHE, $sitemapFilePath);

        if ($this->isSitemapFileUpToDate($timestamp) !== true) {
            return null;
        }

        return $this->flysystemService->readStream(
            SitemapConstants::FILESYSTEM_NAME_CACHE,
            $sitemapFilePath,
        );
    }

    /**
     * @param int|null $fileTimestamp
     *
     * @return bool
     */
    protected function isSitemapFileUpToDate(?int $fileTimestamp): bool
    {
        if ($fileTimestamp === null) {
            return false;
        }

        $now = new DateTime();
        $fileDate = (new DateTime())->setTimestamp($fileTimestamp);

        $interval = $now->getTimestamp() - $fileDate->getTimestamp();

        return $interval <= $this->config->getSitemapFileTimeThreshold();
    }
}
