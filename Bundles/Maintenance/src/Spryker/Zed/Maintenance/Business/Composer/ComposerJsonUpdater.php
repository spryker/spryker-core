<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\Composer;

use Spryker\Zed\Maintenance\Business\Composer\Updater\UpdaterInterface;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\Word\CamelCaseToDash;
use Zend\Filter\Word\DashToCamelCase;

class ComposerJsonUpdater implements ComposerJsonUpdaterInterface
{

    /**
     * @var ComposerJsonFinderInterface
     */
    private $finder;

    /**
     * @var UpdaterInterface[]
     */
    private $updater;

    /**
     * @param ComposerJsonFinderInterface $finder
     * @param UpdaterInterface[] $updater
     */
    public function __construct(ComposerJsonFinderInterface $finder, array $updater)
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
     * @param SplFileInfo $composerJsonFile
     *
     * @return void
     */
    private function updateComposerJsonFile(SplFileInfo $composerJsonFile)
    {
        $composerJson = json_decode($composerJsonFile->getContents(), true);

        foreach ($this->updater as $updater) {
            $composerJson = $updater->update($composerJson);
        }

        file_put_contents($composerJsonFile->getPathname(), json_encode($composerJson, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        echo '<pre>' . PHP_EOL . \Symfony\Component\VarDumper\VarDumper::dump($composerJsonFile) . PHP_EOL . 'Line: ' . __LINE__ . PHP_EOL . 'File: ' . __FILE__ . die();
    }

}
