<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Translator\TranslationCache;

use Spryker\Service\Translator\TranslatorConfig;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class CacheCleaner implements CacheCleanerInterface
{
    /**
     * @var \Spryker\Service\Translator\TranslatorConfig
     */
    protected $config;

    /**
     * @param \Spryker\Service\Translator\TranslatorConfig $config
     */
    public function __construct(TranslatorConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function cleanTranslationCache(): void
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
        if (!file_exists($directory)) {
            return;
        }

        $fileSystem = new Filesystem();
        $fileSystem->remove($this->findFiles($directory));
    }

    /**
     * @param string $directory
     *
     * @return \Symfony\Component\Finder\Finder
     */
    protected function findFiles(string $directory): Finder
    {
        $finder = new Finder();
        $finder
            ->in($directory)
            ->depth(0);

        return $finder;
    }
}
