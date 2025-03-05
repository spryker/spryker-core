<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Sitemap;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Sitemap\Dependency\Client\SitemapToStoreClientInterface;
use Spryker\Yves\Sitemap\Dependency\Service\SitemapToFileSystemServiceInterface;
use Spryker\Yves\Sitemap\Dependency\Service\SitemapToFlysystemServiceInterface;
use Spryker\Yves\Sitemap\Reader\Cache\SitemapCacheReader;
use Spryker\Yves\Sitemap\Reader\SitemapReader;
use Spryker\Yves\Sitemap\Reader\SitemapReaderInterface;
use Spryker\Yves\Sitemap\Writer\SitemapWriter;
use Spryker\Yves\Sitemap\Writer\SitemapWriterInterface;

/**
 * @method \Spryker\Yves\Sitemap\SitemapConfig getConfig()
 */
class SitemapFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Sitemap\Reader\SitemapReaderInterface
     */
    public function createSitemapReader(): SitemapReaderInterface
    {
        return new SitemapReader(
            $this->getFileSystemService(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Yves\Sitemap\Reader\SitemapReaderInterface
     */
    public function createSitemapCacheReader(): SitemapReaderInterface
    {
        return new SitemapCacheReader(
            $this->getFlysystemService(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Yves\Sitemap\Writer\SitemapWriterInterface
     */
    public function createSitemapWriter(): SitemapWriterInterface
    {
        return new SitemapWriter(
            $this->getFileSystemService(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Yves\Sitemap\Dependency\Client\SitemapToStoreClientInterface
     */
    public function getStoreClient(): SitemapToStoreClientInterface
    {
        return $this->getProvidedDependency(SitemapDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Yves\Sitemap\Dependency\Service\SitemapToFileSystemServiceInterface
     */
    public function getFileSystemService(): SitemapToFileSystemServiceInterface
    {
        return $this->getProvidedDependency(SitemapDependencyProvider::SERVICE_FILE_SYSTEM);
    }

    /**
     * @return \Spryker\Yves\Sitemap\Dependency\Service\SitemapToFlysystemServiceInterface
     */
    public function getFlysystemService(): SitemapToFlysystemServiceInterface
    {
        return $this->getProvidedDependency(SitemapDependencyProvider::SERVICE_FLYSYSTEM);
    }
}
