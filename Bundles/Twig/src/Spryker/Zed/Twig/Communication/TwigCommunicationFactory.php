<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Communication;

use Spryker\Shared\Twig\Cache\Cache\FilesystemCache;
use Spryker\Shared\Twig\Cache\CacheLoader\FilesystemCacheLoader;
use Spryker\Shared\Twig\Cache\CacheWriter\FilesystemCacheWriter;
use Spryker\Shared\Twig\TemplateNameExtractor\TemplateNameExtractor;
use Spryker\Shared\Twig\TwigFilesystemLoader;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Twig\TwigDependencyProvider;

/**
 * @method \Spryker\Zed\Twig\TwigConfig getConfig()
 * @method \Spryker\Zed\Twig\Business\TwigFacadeInterface getFacade()
 */
class TwigCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Twig_LoaderInterface
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
