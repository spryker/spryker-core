<?php

namespace SprykerFeature\Shared\Library\Bundle;

use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\Library\Filter\CamelCaseToSeparatorFilter;
use SprykerFeature\Shared\System\SystemConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class BundleConfig
{

    const ACTIVATE_BOOTSTRAP = 'activate bootstrap';
    const ACTIVATE_NAVIGATION = 'activate navigation';
    const ACTIVATE_SCHEMA = 'activate schema';
    const VENDOR = 'Vendor';

    /**
     * @var array
     */
    protected $bundleConfig;

    /**
     * @var array
     */
    protected $config;

    /**
     * @return array
     */
    protected function getConfig()
    {
        if (!$this->bundleConfig) {
            $this->bundleConfig = $this->loadConfig();
        }
        return $this->bundleConfig;
    }

    /**
     * @return array
     * @codeCoverageIgnore
     */
    protected function loadConfig()
    {
        if (!$this->config) {
            if (defined('SYSTEM_UNDER_TEST') && SYSTEM_UNDER_TEST) {
                $this->config = [];
            } else {
                $this->config = require (APPLICATION_ROOT_DIR . '/config/Zed/bundles.php');
            }
        }

        return $this->config;
    }

    /**
     * @return array
     */
    public function getActiveBootstraps()
    {
        return $this->getActiveElements(self::ACTIVATE_BOOTSTRAP);
    }

    /**
     * @return array
     */
    public function getActiveSchemas()
    {
        $schemas = $this->getActiveElements(self::ACTIVATE_SCHEMA);
        $schemaFiles = [];
        foreach ($schemas as $namespace => $bundles) {
            foreach ($bundles as $bundle) {
                $schemaFiles[] = $this->getBundleSchemaPath($namespace, $bundle);
            }
        }

        return $schemaFiles;
    }

    /**
     * TODO refactor me! Maybe Namespace could be just Core or Project
     *
     * @param $namespace
     * @param $bundleName
     * @return string
     * @throws \Exception
     */
    protected function getBundleSchemaPath($namespace, $bundleName)
    {
        $finder = new Finder();
        $filter = new CamelCaseToSeparatorFilter('_');
        $filteredBundleName = strtolower($filter->filter($bundleName));

        $dirs = [];

        if (count(glob(APPLICATION_SOURCE_DIR . '*/Zed/*/Persistence/Propel/Schema/'))) {
            $dirs[] = APPLICATION_SOURCE_DIR . '*/Zed/*/Persistence/Propel/Schema/';
        }
        if (count(glob(APPLICATION_VENDOR_DIR . '*/*/src/*/Zed/*/Persistence/Propel/Schema/'))) {
            $dirs[] = APPLICATION_VENDOR_DIR . '*/*/src/*/Zed/*/Persistence/Propel/Schema/';
        }

        $files = $finder->files()->in($dirs)->name('*_' . $filteredBundleName . '.schema.xml');

        if ($files->count()) {
            /* @var $file SplFileInfo */
            $file = $files->getIterator()->current();

            return $file->getPathname();
        }

        throw new \Exception(sprintf('Couldn\'t find schema file for "%s" bundle', $bundleName));
    }

    /**
     * @return array
     */
    public function getActiveNavigations()
    {
        $activeElements = $this->getActiveElements(self::ACTIVATE_NAVIGATION);
        $files = [];
        foreach ($activeElements as $namespace => $bundles) {
            foreach ($bundles as $bundle) {
                $files[] = $this->getBundleNavigationPath($namespace, $bundle);
            }
        }

        return $files;
    }

    /**
     * @param $namespace
     * @param $bundleName
     * @return string
     * @throws \Exception
     */
    protected function getBundleNavigationPath($namespace, $bundleName)
    {
        $path = $this->getBasePath($namespace, $bundleName);
        $path .= '/Communication/navigation.xml';

        if (!file_exists($path)) {
            throw new \Exception('Couldn\'t find file: ' . $path);
        }

        return $path;
    }

    /**
     * @param $lookupElement
     * @return array
     */
    protected function getActiveElements($lookupElement)
    {
        $activeElements = [];
        foreach ($this->getConfig() as $namespace => $bundles) {
            foreach ($bundles as $bundle => $elements) {
                foreach ($elements as $element) {
                    if ($element === $lookupElement) {
                        $activeElements[$namespace][] = $bundle;
                    }
                }
            }
        }
        return $activeElements;
    }

    /**
     * @param $namespace
     * @param $bundleName
     * @return string
     */
    protected function getBasePath($namespace, $bundleName)
    {
        if ($namespace === self::VENDOR) {
            $path = APPLICATION_VENDOR_DIR . '/*/*/src/*/Zed/' . $bundleName;
            $globPath = glob($path);
            if (count($globPath)) {
                $path = $globPath[0];
            }
        } else {
            $projectNamespace = Config::get(SystemConfig::PROJECT_NAMESPACE);
            $path = APPLICATION_SOURCE_DIR . '/' . $projectNamespace;
            $path .= '/Zed/' . $bundleName;
        }

        return $path;
    }
}
