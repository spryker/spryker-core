<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer;

use Spryker\Zed\Development\Business\Composer\Updater\UpdaterInterface;
use Symfony\Component\Finder\SplFileInfo;

class ComposerJsonUpdater implements ComposerJsonUpdaterInterface
{

    const REPLACE_4_WITH_2_SPACES = '/^(  +?)\\1(?=[^ ])/m';

    /**
     * @var \Spryker\Zed\Development\Business\Composer\ComposerJsonFinderInterface
     */
    protected $finder;

    /**
     * @var \Spryker\Zed\Development\Business\Composer\Updater\UpdaterInterface
     */
    protected $updater;

    /**
     * @param \Spryker\Zed\Development\Business\Composer\ComposerJsonFinderInterface $finder
     * @param \Spryker\Zed\Development\Business\Composer\Updater\UpdaterInterface $updater
     */
    public function __construct(ComposerJsonFinderInterface $finder, UpdaterInterface $updater)
    {
        $this->finder = $finder;
        $this->updater = $updater;
    }

    /**
     * @param array $bundles
     *
     * @return void
     */
    public function update(array $bundles)
    {
        $composerJsonFiles = $this->finder->find();
        foreach ($composerJsonFiles as $composerJsonFile) {
            if ($this->shouldSkip($composerJsonFile, $bundles)) {
                continue;
            }

            $this->updateComposerJsonFile($composerJsonFile);
        }
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return void
     */
    protected function updateComposerJsonFile(SplFileInfo $composerJsonFile)
    {
        exec('./composer.phar validate ' . $composerJsonFile->getPathname(), $output, $return);
        if ($return !== 0) {
            throw new \RuntimeException('Invalid composer file ' . $composerJsonFile->getPathname() . ': ' . print_r($output, true));
        }

        $composerJson = json_decode($composerJsonFile->getContents(), true);

        $composerJson = $this->updater->update($composerJson);

        ksort($composerJson['require']);

        $composerJson = json_encode($composerJson, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        $composerJson = preg_replace(self::REPLACE_4_WITH_2_SPACES, '$1', $composerJson) . PHP_EOL;

        file_put_contents($composerJsonFile->getPathname(), $composerJson);
    }

    /**
     * @param SplFileInfo $composerJsonFile
     * @param array $bundles
     *
     * @return bool
     */
    protected function shouldSkip(SplFileInfo $composerJsonFile, array $bundles)
    {
        if (!$bundles) {
            return false;
        }

        $folder = $composerJsonFile->getRelativePath();
        return !in_array($folder, $bundles);
    }

}
