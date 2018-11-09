<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Phpstan\Config;

use SplFileInfo;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\Finder;

class PhpstanConfigFileFinder implements PhpstanConfigFileFinderInterface
{
    protected const DEPTH_FOR_CONFIG_FILE_FINDER = '== 0';
    protected const PRESERVE_ITERATOR_KEYS = false;

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(Finder $finder, DevelopmentConfig $config)
    {
        $this->finder = $finder;
        $this->config = $config;
    }

    /**
     * @param string $directoryPath
     *
     * @return \SplFileInfo|null
     */
    public function searchIn(string $directoryPath): ?SplFileInfo
    {
        $this->clearFinder();
        $this->addDirectoryToFinder($directoryPath);

        return $this->getConfigFile();
    }

    /**
     * @return \SplFileInfo|null
     */
    protected function getConfigFile(): ?SplFileInfo
    {
        if (!$this->finder->hasResults()) {
            return null;
        }

        return iterator_to_array($this->finder, static::PRESERVE_ITERATOR_KEYS)[0];
    }

    /**
     * @param string $directoryPath
     *
     * @return void
     */
    protected function addDirectoryToFinder(string $directoryPath): void
    {
        $this->finder->in($directoryPath);
    }

    /**
     * @return void
     */
    protected function clearFinder(): void
    {
        $this->finder = $this->finder::create()
            ->name($this->config->getPhpstanConfigFilename())
            ->depth(static::DEPTH_FOR_CONFIG_FILE_FINDER);
    }
}
