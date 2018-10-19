<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\Cache;

use Spryker\Zed\Translator\TranslatorConfig;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class CacheClearer implements CacheClearerInterface
{
    /**
     * @var \Spryker\Zed\Translator\TranslatorConfig
     */
    protected $config;

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $fileSystem;

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @param \Spryker\Zed\Translator\TranslatorConfig $config
     * @param \Symfony\Component\Filesystem\Filesystem $fileSystem
     * @param \Symfony\Component\Finder\Finder $finder
     */
    public function __construct(TranslatorConfig $config, Filesystem $fileSystem, Finder $finder)
    {
        $this->config = $config;
        $this->fileSystem = $fileSystem;
        $this->finder = $finder;
    }

    /**
     * @return void
     */
    public function clearCache(): void
    {
        $this->clearDirectory($this->config->getCacheDir());
    }

    /**
     * @param string $directory
     *
     * @return void
     */
    protected function clearDirectory(string $directory): void
    {
        $this->fileSystem->remove($this->findFiles($directory));
    }

    /**
     * @param string $directory
     *
     * @return \Symfony\Component\Finder\Finder
     */
    protected function findFiles(string $directory): Finder
    {
        $finder = clone $this->finder;
        $finder
            ->in($directory)
            ->depth(0);

        return $finder;
    }
}
