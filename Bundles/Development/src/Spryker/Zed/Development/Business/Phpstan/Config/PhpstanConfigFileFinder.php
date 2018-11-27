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

        return iterator_to_array($this->finder, false)[0];
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
            ->depth('== 0');
    }
}
