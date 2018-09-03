<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Business;

use Spryker\Shared\Twig\Cache\CacheWriter\FilesystemCacheWriter;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Twig\Business\Model\CacheWarmer\CacheWarmer;
use Spryker\Zed\Twig\Business\Model\CacheWarmer\CacheWarmerComposite;
use Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilder\TemplateNameBuilderYves;
use Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilder\TemplateNameBuilderZed;
use Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplatePathMapBuilder;
use Symfony\Component\Finder\Finder;

/**
 * @method \Spryker\Zed\Twig\TwigConfig getConfig()
 */
class TwigBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Twig\Business\Model\CacheWarmerInterface
     */
    public function createCacheWarmer()
    {
        $cacheWarmerComposite = new CacheWarmerComposite([
            $this->createCacheWarmerForZed(),
            $this->createCacheWarmerForYves(),
        ]);

        return $cacheWarmerComposite;
    }

    /**
     * @return \Spryker\Zed\Twig\Business\Model\CacheWarmerInterface
     */
    protected function createCacheWarmerForZed()
    {
        return new CacheWarmer($this->createCacheWriterForZed(), $this->createTemplatePathMapBuilderForZed());
    }

    /**
     * @return \Spryker\Shared\Twig\Cache\CacheWriterInterface
     */
    protected function createCacheWriterForZed()
    {
        return new FilesystemCacheWriter(
            $this->getConfig()->getCacheFilePath(),
            $this->getConfig()->getPermissionMode()
        );
    }

    /**
     * @return \Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplatePathMapBuilder
     */
    protected function createTemplatePathMapBuilderForZed()
    {
        $templatePathMapBuilder = new TemplatePathMapBuilder(
            $this->createFinder(),
            $this->createTemplateNameBuilderZed(),
            $this->getConfig()->getZedDirectoryPathPatterns()
        );

        return $templatePathMapBuilder;
    }

    /**
     * @return \Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilderInterface
     */
    protected function createTemplateNameBuilderZed()
    {
        return new TemplateNameBuilderZed();
    }

    /**
     * @return \Spryker\Zed\Twig\Business\Model\CacheWarmerInterface
     */
    protected function createCacheWarmerForYves()
    {
        return new CacheWarmer($this->createCacheWriterForYves(), $this->createTemplatePathMapBuilderForYves());
    }

    /**
     * @return \Spryker\Shared\Twig\Cache\CacheWriterInterface
     */
    protected function createCacheWriterForYves()
    {
        return new FilesystemCacheWriter(
            $this->getConfig()->getCacheFilePathForYves(),
            $this->getConfig()->getPermissionMode()
        );
    }

    /**
     * @return \Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplatePathMapBuilder
     */
    protected function createTemplatePathMapBuilderForYves()
    {
        $templatePathMapBuilder = new TemplatePathMapBuilder(
            $this->createFinder(),
            $this->createTemplateNameBuilderYves(),
            $this->getConfig()->getYvesDirectoryPathPatterns()
        );

        return $templatePathMapBuilder;
    }

    /**
     * @return \Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilderInterface
     */
    protected function createTemplateNameBuilderYves()
    {
        return new TemplateNameBuilderYves();
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function createFinder()
    {
        return new Finder();
    }
}
