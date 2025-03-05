<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Sitemap\Reader;

use Generated\Shared\Transfer\FileSystemStreamTransfer;
use Spryker\Shared\Sitemap\SitemapConstants;
use Spryker\Yves\Sitemap\Dependency\Service\SitemapToFileSystemServiceInterface;
use Spryker\Yves\Sitemap\SitemapConfig;

class SitemapReader implements SitemapReaderInterface
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
     *
     * @return mixed
     */
    public function readStream(string $sitemapFileName, string $storeName): mixed
    {
        $fileSystemStreamTransfer = (new FileSystemStreamTransfer())
            ->setFileSystemName(SitemapConstants::FILESYSTEM_NAME)
            ->setPath($this->config->getFilePath($storeName, $sitemapFileName));

        return $this->fileSystemService->readStream($fileSystemStreamTransfer);
    }
}
