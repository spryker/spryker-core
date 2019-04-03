<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Communication;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Twig\Cache\Cache\FilesystemCache;
use Spryker\Shared\Twig\Cache\CacheLoader\FilesystemCacheLoader;
use Spryker\Shared\Twig\Cache\CacheWriter\FilesystemCacheWriter;
use Spryker\Shared\Twig\Loader\FilesystemLoader;
use Spryker\Shared\Twig\Loader\FilesystemLoaderInterface;
use Spryker\Shared\Twig\Loader\TwigChainLoader;
use Spryker\Shared\Twig\Loader\TwigChainLoaderInterface;
use Spryker\Shared\Twig\TemplateNameExtractor\TemplateNameExtractor;
use Spryker\Shared\Twig\TwigFilesystemLoader;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Twig\Communication\RouteResolver\RouteResolver;
use Spryker\Zed\Twig\Communication\RouteResolver\RouteResolverInterface;
use Spryker\Zed\Twig\Communication\Subscriber\TwigEventSubscriber;
use Spryker\Zed\Twig\TwigDependencyProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @method \Spryker\Zed\Twig\TwigConfig getConfig()
 * @method \Spryker\Zed\Twig\Business\TwigFacadeInterface getFacade()
 */
class TwigCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\Twig\Loader\FilesystemLoaderInterface
     */
    public function createFilesystemLoader()
    {
        return new TwigFilesystemLoader(
            $this->getConfig()->getTemplatePaths(),
            $this->createFilesystemCache(),
            $this->createTemplateNameExtractor()
        );
    }

    /**
     * @return \Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface[]
     */
    public function getTwigPlugins(): array
    {
        return $this->getProvidedDependency(TwigDependencyProvider::PLUGINS_TWIG);
    }

    /**
     * @return \Spryker\Shared\Twig\Loader\TwigChainLoaderInterface
     */
    public function createTwigChainLoader(): TwigChainLoaderInterface
    {
        return new TwigChainLoader(
            $this->getTwigLoaderPlugins()
        );
    }

    /**
     * @return \Spryker\Shared\Twig\Loader\FilesystemLoaderInterface
     */
    public function createTwigFilesystemLoader(): FilesystemLoaderInterface
    {
        return new FilesystemLoader($this->getConfig()->getFormTemplateDirectories());
    }

    /**
     * @return \Spryker\Shared\TwigExtension\Dependency\Plugin\TwigLoaderPluginInterface[]
     */
    public function getTwigLoaderPlugins(): array
    {
        return $this->getProvidedDependency(TwigDependencyProvider::PLUGINS_TWIG_LOADER);
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function createTwigEventSubscriber(ContainerInterface $container): EventSubscriberInterface
    {
        return new TwigEventSubscriber($container, $this->createRouteResolver());
    }

    /**
     * @return \Spryker\Zed\Twig\Communication\RouteResolver\RouteResolverInterface
     */
    public function createRouteResolver(): RouteResolverInterface
    {
        return new RouteResolver();
    }

    /**
     * @return \Spryker\Shared\Twig\Cache\CacheInterface
     */
    protected function createFilesystemCache()
    {
        $filesystemLoaderCache = new FilesystemCache(
            $this->createFilesystemCacheLoader(),
            $this->createFilesystemCacheWriter(),
            $this->getConfig()->isPathCacheEnabled()
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
            $this->getConfig()->getPermissionMode()
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
}
