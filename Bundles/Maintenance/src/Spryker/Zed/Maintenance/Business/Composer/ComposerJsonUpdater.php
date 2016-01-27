<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\Composer;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\Word\CamelCaseToDash;
use Zend\Filter\Word\DashToCamelCase;

class ComposerJsonUpdater
{

    /**
     * @var DependencyTreeReaderInterface
     */
    private $dependencyTreeReader;

    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var string
     */
    private $pathToBundles;

    /**
     * @param DependencyTreeReaderInterface $dependencyTreeReader
     * @param Finder $finder
     * @param $pathToBundles
     */
    public function __construct(DependencyTreeReaderInterface $dependencyTreeReader, Finder $finder, $pathToBundles)
    {
        $this->dependencyTreeReader = $dependencyTreeReader;
        $this->finder = $finder;
        $this->pathToBundles = $pathToBundles;
    }

    /**
     * @return void
     */
    public function update()
    {
        $dependencyTree = $this->dependencyTreeReader->read();
        $composerJsonFiles = $this->getComposerJsonFiles();
        foreach ($composerJsonFiles as $composerJsonFile) {
            $this->updateComposerJsonFile($composerJsonFile, $dependencyTree);
        }
    }

    /**
     * @return Finder|SplFileInfo[]
     */
    private function getComposerJsonFiles()
    {
        return $this->finder->in($this->pathToBundles)->name('composer.json');
    }

    /**
     * @param SplFileInfo $composerJsonFile
     * @param array $dependencyTree
     *
     * @return void
     */
    private function updateComposerJsonFile(SplFileInfo $composerJsonFile, array $dependencyTree)
    {
        $composerJsonData = json_decode($composerJsonFile->getContents(), true);
        $bundleName = $this->getBundleName($composerJsonData);

        $dependentBundles = $this->getDependentBundles($bundleName, $dependencyTree);
        if (!isset($composerJsonData['require'])) {
            $composerJsonData['require'] = [];
        }

        foreach ($dependentBundles as $dependentBundle) {
            $filter = new CamelCaseToDash();
            $dependentBundle = strtolower($filter->filter($dependentBundle));

            $dependency = ['spryker/' . $dependentBundle => '^1.0.0'];

            $composerJsonData['require'][] = $dependency;
        }

        file_put_contents($composerJsonFile->getPathname(), json_encode($composerJsonData));
        echo '<pre>' . PHP_EOL . \Symfony\Component\VarDumper\VarDumper::dump($composerJsonFile) . PHP_EOL . 'Line: ' . __LINE__ . PHP_EOL . 'File: ' . __FILE__ . die();
    }

    /**
     * @param array $composerJsonData
     *
     * @return string
     */
    private function getBundleName($composerJsonData)
    {
        $nameParts = explode('/', $composerJsonData['name']);
        $bundleName = array_pop($nameParts);
        $filter = new DashToCamelCase();

        return $filter->filter($bundleName);
    }

    /**
     * @param string $bundleName
     * @param array $dependencyTree
     *
     * @return array
     */
    private function getDependentBundles($bundleName, array $dependencyTree)
    {
        $dependentBundles = [];
        foreach ($dependencyTree as $dependency) {
            if ($dependency[DependencyTree::META_BUNDLE] === $bundleName && !in_array($dependency[DependencyTree::META_FOREIGN_BUNDLE], $dependentBundles)) {
                $dependentBundles[] = $dependency[DependencyTree::META_FOREIGN_BUNDLE];
            }
        }

        return $dependentBundles;
    }

}
