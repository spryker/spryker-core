<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Twig;

use Spryker\Shared\Twig\Cache\Cache\FilesystemCache;
use Spryker\Shared\Twig\Cache\CacheLoader\FilesystemCacheLoader;
use Spryker\Shared\Twig\Cache\CacheWriter\FilesystemCacheWriter;
use Spryker\Shared\Twig\Extender\FilterExtender;
use Spryker\Shared\Twig\Extender\FilterExtenderInterface;
use Spryker\Shared\Twig\Extension\EnvironmentCoreExtension;
use Spryker\Shared\Twig\Extension\EnvironmentCoreExtensionInterface;
use Spryker\Shared\Twig\Filter\FilterFactory;
use Spryker\Shared\Twig\Filter\FilterFactoryInterface;
use Spryker\Shared\Twig\Loader\FilesystemLoader;
use Spryker\Shared\Twig\Loader\FilesystemLoaderInterface;
use Spryker\Shared\Twig\TwigFilesystemLoader;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Twig\Model\TemplateNameExtractor\TemplateNameExtractor;
use Twig\Loader\ChainLoader;

/**
 * @method \Spryker\Yves\Twig\TwigConfig getConfig()
 */
class TwigFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Twig\Loader\FilesystemLoaderInterface
     */
    public function createFilesystemLoader()
    {
        return new TwigFilesystemLoader(
            $this->getConfig()->getTemplatePaths(),
            $this->createFilesystemCache(),
            $this->createTemplateNameExtractor(),
        );
    }

    /**
     * @return array<\Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface>
     */
    public function getTwigPlugins(): array
    {
        return $this->getProvidedDependency(TwigDependencyProvider::PLUGINS_TWIG);
    }

    /**
     * @return \Spryker\Shared\Twig\Cache\CacheInterface
     */
    protected function createFilesystemCache()
    {
        $filesystemLoaderCache = new FilesystemCache(
            $this->createFilesystemCacheLoader(),
            $this->createFilesystemCacheWriter(),
            $this->getConfig()->isPathCacheEnabled(),
        );

        return $filesystemLoaderCache;
    }

    /**
     * @return \Spryker\Shared\Twig\Cache\CacheLoaderInterface
     */
    protected function createFilesystemCacheLoader()
    {
        return new FilesystemCacheLoader($this->getConfig()->getCacheFilePath());
    }

    /**
     * @return \Spryker\Shared\Twig\Cache\CacheWriterInterface
     */
    protected function createFilesystemCacheWriter()
    {
        return new FilesystemCacheWriter(
            $this->getConfig()->getCacheFilePath(),
            $this->getConfig()->getPermissionMode(),
        );
    }

    /**
     * @return \Spryker\Shared\Twig\TemplateNameExtractor\TemplateNameExtractorInterface
     */
    protected function createTemplateNameExtractor()
    {
        return new TemplateNameExtractor($this->getUtilTextService());
    }

    /**
     * @return \Spryker\Shared\Twig\Dependency\Service\TwigToUtilTextServiceInterface
     */
    protected function getUtilTextService()
    {
        return $this->getProvidedDependency(TwigDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Twig\Loader\ChainLoader
     */
    public function createChainLoader(): ChainLoader
    {
        return new ChainLoader();
    }

    /**
     * @return \Spryker\Shared\Twig\Loader\FilesystemLoaderInterface
     */
    public function createTwigFilesystemLoader(): FilesystemLoaderInterface
    {
        return new FilesystemLoader($this->getConfig()->getFormTemplateDirectories());
    }

    /**
     * @return \Spryker\Shared\Twig\Extender\FilterExtenderInterface
     */
    public function createFilterExtender(): FilterExtenderInterface
    {
        return new FilterExtender(
            $this->createFilterFactory(),
            $this->createEnvironmentCoreExtension(),
        );
    }

    /**
     * @return \Spryker\Shared\Twig\Filter\FilterFactoryInterface
     */
    public function createFilterFactory(): FilterFactoryInterface
    {
        return new FilterFactory();
    }

    /**
     * @return \Spryker\Shared\Twig\Extension\EnvironmentCoreExtensionInterface
     */
    public function createEnvironmentCoreExtension(): EnvironmentCoreExtensionInterface
    {
        return new EnvironmentCoreExtension();
    }

    /**
     * @return array<\Spryker\Shared\TwigExtension\Dependency\Plugin\TwigLoaderPluginInterface>
     */
    public function getTwigLoaderPlugins(): array
    {
        return $this->getProvidedDependency(TwigDependencyProvider::PLUGINS_TWIG_LOADER);
    }
}
