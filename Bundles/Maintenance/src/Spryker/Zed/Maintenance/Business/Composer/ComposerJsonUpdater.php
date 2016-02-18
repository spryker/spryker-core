<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\Composer;

use Spryker\Zed\Maintenance\Business\Composer\Updater\UpdaterInterface;
use Symfony\Component\Finder\SplFileInfo;

class ComposerJsonUpdater implements ComposerJsonUpdaterInterface
{

    const REPLACE_4_WITH_2_SPACES = '/^(  +?)\\1(?=[^ ])/m';

    /**
     * @var \Spryker\Zed\Maintenance\Business\Composer\ComposerJsonFinderInterface
     */
    private $finder;

    /**
     * @var \Spryker\Zed\Maintenance\Business\Composer\Updater\UpdaterInterface
     */
    private $updater;

    /**
     * @param \Spryker\Zed\Maintenance\Business\Composer\ComposerJsonFinderInterface $finder
     * @param \Spryker\Zed\Maintenance\Business\Composer\Updater\UpdaterInterface $updater
     */
    public function __construct(ComposerJsonFinderInterface $finder, UpdaterInterface $updater)
    {
        $this->finder = $finder;
        $this->updater = $updater;
    }

    /**
     * @return void
     */
    public function update()
    {
        $composerJsonFiles = $this->finder->find();
        foreach ($composerJsonFiles as $composerJsonFile) {
            $this->updateComposerJsonFile($composerJsonFile);
        }
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return void
     */
    private function updateComposerJsonFile(SplFileInfo $composerJsonFile)
    {
        $composerJson = json_decode($composerJsonFile->getContents(), true);

        $composerJson = $this->updater->update($composerJson);

        $composerJson = json_encode($composerJson, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_FORCE_OBJECT);
        $composerJson = preg_replace(self::REPLACE_4_WITH_2_SPACES, '$1', $composerJson) . PHP_EOL;

        file_put_contents($composerJsonFile->getPathname(), $composerJson);
    }

}
