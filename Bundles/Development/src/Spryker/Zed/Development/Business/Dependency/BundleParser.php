<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency;

use ArrayObject;
use Generated\Shared\Transfer\BundleDependencyCollectionTransfer;
use Generated\Shared\Transfer\DependencyBundleTransfer;
use Generated\Shared\Transfer\DependencyTransfer;
use Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\Finder as SymfonyFinder;
use Zend\Filter\Word\SeparatorToCamelCase;
use Zend\Filter\Word\UnderscoreToCamelCase;

class BundleParser implements BundleParserInterface
{
    /**
     * @var array
     */
    protected $relevantBundleNamespaces = ['Spryker', 'SprykerEco', 'SprykerSdk', 'SprykerShop', 'Orm'];

    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface
     */
    protected $finder;

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $bundleConfig;

    /**
     * @var \Generated\Shared\Transfer\BundleDependencyCollectionTransfer
     */
    protected $bundleDependencyCollectionTransfer;

    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface $finder
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(FinderInterface $finder, DevelopmentConfig $config)
    {
        $this->finder = $finder;
        $this->config = $config;
    }

    /**
     * @param string $bundleName
     *
     * @return \Generated\Shared\Transfer\BundleDependencyCollectionTransfer
     */
    public function parseOutgoingDependencies($bundleName)
    {
        $bundleDependencyCollectionTransfer = new BundleDependencyCollectionTransfer();
        $bundleDependencyCollectionTransfer->setBundle($bundleName);

        $this->bundleDependencyCollectionTransfer = $bundleDependencyCollectionTransfer;

        $allFileDependencies = $this->parseDependencies($bundleName);
        $this->addAllDependencies($allFileDependencies);
        $this->addExternalBundleDependencies($allFileDependencies);
        $this->addLocatorBundleDependencies($allFileDependencies);
        $this->addPersistenceLayerDependencies($bundleName);

        $callback = function (DependencyBundleTransfer $dependencyBundleTransferA, DependencyBundleTransfer $dependencyBundleTransferB) {
            return strcmp($dependencyBundleTransferA->getBundle(), $dependencyBundleTransferB->getBundle());
        };

        $dependencyBundles = $this->bundleDependencyCollectionTransfer->getDependencyBundles()->getArrayCopy();

        usort($dependencyBundles, $callback);

        $this->bundleDependencyCollectionTransfer->setDependencyBundles(new ArrayObject());

        foreach ($dependencyBundles as $dependencyBundle) {
            $this->bundleDependencyCollectionTransfer->addDependencyBundle($dependencyBundle);
        }

        return $this->bundleDependencyCollectionTransfer;
    }

    /**
     * @param array $allFileDependencies
     *
     * @return void
     */
    protected function addAllDependencies(array $allFileDependencies)
    {
        $allFileDependencies = $this->filterRelevantClasses($allFileDependencies);

        $this->buildBundleDependencies($allFileDependencies);
    }

    /**
     * @param array $allFileDependencies
     *
     * @return void
     */
    protected function addExternalBundleDependencies(array $allFileDependencies)
    {
        $map = $this->config->getExternalToInternalNamespaceMap();

        foreach ($allFileDependencies as $file => $fileDependencies) {
            foreach ($fileDependencies as $fileDependency) {
                $found = null;
                foreach ($map as $namespace => $package) {
                    if (strpos($fileDependency, $namespace) !== 0) {
                        continue;
                    }

                    $found = $package;
                    break;
                }

                if ($found === null) {
                    continue;
                }

                $foreignBundle = substr($found, 8);
                $filter = new SeparatorToCamelCase('-');
                $foreignBundle = ucfirst($filter->filter($foreignBundle));

                $dependencyTransfer = new DependencyTransfer();
                $dependencyTransfer
                    ->setBundle($foreignBundle)
                    ->setType('external')
                    ->setIsOptional($this->isPluginFile($file))
                    ->setIsInTest($this->isTestFile($file));

                $this->addDependency($dependencyTransfer);
            }
        }
    }

    /**
     * @param string $file
     *
     * @return bool
     */
    protected function isTestFile($file)
    {
        return !strpos($file, '/src/');
    }

    /**
     * @param string $file
     *
     * @return bool
     */
    protected function isPluginFile($file)
    {
        return (strpos($file, '/Plugin/') !== false);
    }

    /**
     * We only detect dependencies which are declared in the class' use statement
     *
     * @param string $module
     *
     * @return array
     */
    protected function parseDependencies($module)
    {
        $files = $this->finder->find($module);

        $dependencies = [];
        foreach ($files as $file) {
            $content = $file->getContents();

            $matches = [];
            preg_match_all('#use (.*);#', $content, $matches);

            $dependencies[$file->getPath() . '/' . $file->getFilename()] = $matches[1];
        }

        return $dependencies;
    }

    /**
     * @param string $bundle
     *
     * @return bool
     */
    protected function isExistentBundle($bundle)
    {
        if (is_dir($this->config->getPathToCore() . $bundle)) {
            return true;
        }

        return false;
    }

    /**
     * @param array $dependencies
     *
     * @return array
     */
    protected function filterRelevantClasses(array $dependencies)
    {
        $reducedDependenciesPerFile = [];
        foreach ($dependencies as $fileName => $fileDependencies) {
            $reducedDependencies = [];
            foreach ($fileDependencies as $fileDependency) {
                $fileDependencyParts = explode('\\', $fileDependency);
                $bundleNamespace = $fileDependencyParts[0];
                if (in_array($bundleNamespace, $this->relevantBundleNamespaces)) {
                    $reducedDependencies[] = $fileDependency;
                }
            }
            $reducedDependenciesPerFile[$fileName] = $reducedDependencies;
        }

        return $reducedDependenciesPerFile;
    }

    /**
     * @param array $allFileDependencies
     *
     * @return void
     */
    protected function buildBundleDependencies(array $allFileDependencies)
    {
        foreach ($allFileDependencies as $file => $fileDependencies) {
            foreach ($fileDependencies as $fileDependency) {
                $fileNameParts = explode('\\', $fileDependency);
                $foreignModule = $fileNameParts[2];
                if ($this->bundleDependencyCollectionTransfer->getBundle() !== $foreignModule) {
                    $dependencyTransfer = new DependencyTransfer();
                    $dependencyTransfer->setBundle($foreignModule);
                    $dependencyTransfer->setType('spryker');
                    $dependencyTransfer->setIsOptional($this->isPluginFile($file) && !$this->isExtensionModule($foreignModule));
                    $dependencyTransfer->setIsInTest($this->isTestFile($file));

                    $this->addDependency($dependencyTransfer);
                }
            }
        }
    }

    /**
     * @param string $moduleName
     *
     * @return bool
     */
    protected function isExtensionModule($moduleName)
    {
        return preg_match('/Extension$/', $moduleName);
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyTransfer $dependencyTransfer
     *
     * @return void
     */
    protected function addDependency(DependencyTransfer $dependencyTransfer)
    {
        $dependencyBundleTransfer = $this->getDependencyBundleTransfer($dependencyTransfer);
        $dependencyBundleTransfer->addDependency($dependencyTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyTransfer $dependencyTransfer
     *
     * @return \Generated\Shared\Transfer\DependencyBundleTransfer
     */
    protected function getDependencyBundleTransfer(DependencyTransfer $dependencyTransfer)
    {
        foreach ($this->bundleDependencyCollectionTransfer->getDependencyBundles() as $dependencyBundleTransfer) {
            if ($dependencyBundleTransfer->getBundle() === $dependencyTransfer->getBundle()) {
                return $dependencyBundleTransfer;
            }
        }

        $dependencyBundleTransfer = new DependencyBundleTransfer();
        $dependencyBundleTransfer->setBundle($dependencyTransfer->getBundle());

        $this->bundleDependencyCollectionTransfer->addDependencyBundle($dependencyBundleTransfer);

        return $dependencyBundleTransfer;
    }

    /**
     * @param string $bundleName
     *
     * @return void
     */
    protected function addPersistenceLayerDependencies($bundleName)
    {
        $folder = $this->config->getPathToCore() . $bundleName . '/src/Spryker/Zed/' . $bundleName . '/Persistence/Propel/Schema/';
        if (!is_dir($folder)) {
            return;
        }

        $files = $this->find($folder);

        foreach ($files as $file) {
            preg_match('/^spy\_(.+)\.schema\.xml$/', $file->getRelativePathname(), $matches);
            if (!$matches) {
                continue;
            }

            $name = $matches[1];

            $content = file_get_contents($file->getPathname());
            preg_match_all('/\bforeignTable="spy\_(.+?)"/', $content, $tableMatches);

            $tables = $tableMatches[1];
            foreach ($tables as $key => $value) {
                if (strpos($value, $name) === 0) {
                    unset($tables[$key]);
                }
            }

            if (!$tables) {
                continue;
            }

            foreach ($tables as $table) {
                $this->addPersistenceLayerDependency($table);
            }
        }
    }

    /**
     * @param string $table
     *
     * @return void
     */
    protected function addPersistenceLayerDependency($table)
    {
        $filter = new UnderscoreToCamelCase();
        $name = (string)$filter->filter($table);

        $existent = $this->isExistentBundle($name);
        if ($existent) {
            $dependencyTransfer = new DependencyTransfer();
            $dependencyTransfer
                ->setBundle($name)
                ->setType('spryker (persistence)')
                ->setIsInTest(false);

            $this->addDependency($dependencyTransfer);

            return;
        }

        $lastUnderscore = strrpos($table, '_');

        while ($lastUnderscore) {
            $table = substr($table, 0, $lastUnderscore);

            $filter = new UnderscoreToCamelCase();
            $name = (string)$filter->filter($table);

            $existent = $this->isExistentBundle($name);
            if (!$existent) {
                $lastUnderscore = strrpos($table, '_');
                continue;
            }

            $dependencyTransfer = new DependencyTransfer();
            $dependencyTransfer
                ->setBundle($name)
                ->setType('spryker (persistence)')
                ->setIsInTest(false);

            break;
        }
    }

    /**
     * @param string $folder
     *
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function find($folder)
    {
        $finder = new SymfonyFinder();

        return $finder->in($folder)->name('*.schema.xml')->depth('< 2');
    }

    /**
     * @param array $allFileDependencies
     *
     * @return void
     */
    protected function addLocatorBundleDependencies(array $allFileDependencies)
    {
        foreach ($allFileDependencies as $fileName => $fileDependencies) {
            if (!$fileDependencies || strpos($fileName, 'DependencyProvider.php') === false) {
                continue;
            }

            $this->addDependenciesFromDependencyProvider($fileName);
        }
    }

    /**
     * @param string $fileName
     *
     * @return void
     */
    protected function addDependenciesFromDependencyProvider($fileName)
    {
        $content = file_get_contents($fileName);

        if (!preg_match_all('/->(?<bundle>\w+?)\(\)->(client|facade|queryContainer|service)\(\)/', $content, $matches, PREG_SET_ORDER)) {
            return;
        }

        foreach ($matches as $match) {
            $toBundle = ucfirst($match['bundle']);
            $dependencyTransfer = new DependencyTransfer();
            $dependencyTransfer
                ->setBundle($toBundle)
                ->setType('spryker (locator)')
                ->setIsInTest(false);

            $this->addDependency($dependencyTransfer);
        }
    }
}
