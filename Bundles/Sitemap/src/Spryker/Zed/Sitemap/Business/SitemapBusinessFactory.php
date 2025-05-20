<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sitemap\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Sitemap\Business\Generator\SitemapGenerator;
use Spryker\Zed\Sitemap\Business\Generator\SitemapGeneratorInterface;
use Spryker\Zed\Sitemap\Business\Generator\XmlGenerator;
use Spryker\Zed\Sitemap\Business\Generator\XmlGeneratorInterface;
use Spryker\Zed\Sitemap\Dependency\Facade\SitemapToStoreFacadeInterface;
use Spryker\Zed\Sitemap\Dependency\Service\SitemapToFileSystemServiceInterface;
use Spryker\Zed\Sitemap\SitemapDependencyProvider;

/**
 * @method \Spryker\Zed\Sitemap\SitemapConfig getConfig()
 */
class SitemapBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Sitemap\Business\Generator\XmlGeneratorInterface
     */
    public function createXmlGenerator(): XmlGeneratorInterface
    {
        return new XmlGenerator(
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Sitemap\Business\Generator\SitemapGeneratorInterface
     */
    public function createSitemapGenerator(): SitemapGeneratorInterface
    {
        return new SitemapGenerator(
            $this->getStoreFacade(),
            $this->createXmlGenerator(),
            $this->getFileSystemService(),
            $this->getConfig(),
            $this->getSitemapGeneratorDataProviderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Sitemap\Dependency\Service\SitemapToFileSystemServiceInterface
     */
    public function getFileSystemService(): SitemapToFileSystemServiceInterface
    {
        return $this->getProvidedDependency(SitemapDependencyProvider::SERVICE_FILE_SYSTEM);
    }

    /**
     * @return \Spryker\Zed\Sitemap\Dependency\Facade\SitemapToStoreFacadeInterface
     */
    public function getStoreFacade(): SitemapToStoreFacadeInterface
    {
        return $this->getProvidedDependency(SitemapDependencyProvider::FACADE_STORE);
    }

    /**
     * @return array<\Spryker\Zed\SitemapExtension\Dependency\Plugin\SitemapGeneratorDataProviderPluginInterface>
     */
    public function getSitemapGeneratorDataProviderPlugins(): array
    {
        return $this->getProvidedDependency(SitemapDependencyProvider::PLUGINS_SITEMAP_GENERATOR_DATA_PROVIDER);
    }
}
