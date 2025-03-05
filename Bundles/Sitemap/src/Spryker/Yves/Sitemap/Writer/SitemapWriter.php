<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Sitemap\Writer;

use Generated\Shared\Transfer\FileSystemStreamTransfer;
use Spryker\Shared\Sitemap\SitemapConstants;
use Spryker\Yves\Sitemap\Dependency\Service\SitemapToFileSystemServiceInterface;
use Spryker\Yves\Sitemap\SitemapConfig;

class SitemapWriter implements SitemapWriterInterface
{
    /**
     * @param \Spryker\Yves\Sitemap\Dependency\Service\SitemapToFileSystemServiceInterface $fileSystemService
     * @param \Spryker\Yves\Sitemap\SitemapConfig $config
     */
    public function __construct(
        protected SitemapToFileSystemServiceInterface $fileSystemService,
        protected SitemapConfig $config
    ) {
    }

    /**
     * @param string $sitemapFileName
     * @param string $storeName
     * @param mixed $stream
     *
     * @return void
     */
    public function writeStream(
        string $sitemapFileName,
        string $storeName,
        $stream
    ): void {
        $fileSystemStreamTransfer = (new FileSystemStreamTransfer())
            ->setFileSystemName(SitemapConstants::FILESYSTEM_NAME_CACHE)
            ->setPath($this->config->getFilePath($storeName, $sitemapFileName));

        $this->fileSystemService->writeStream($fileSystemStreamTransfer, $stream);
    }
}
