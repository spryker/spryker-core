<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use Symfony\Component\Finder\SplFileInfo;

class AutoloadUpdater implements UpdaterInterface
{
    /**
     * @var array
     */
    protected $testDirectoryKeys = [
        'Acceptance',
        'Functional',
        'Integration',
        'Unit',
    ];

    /**
     * @var array
     */
    protected $applications = [
        'Client',
        'Shared',
        'Yves',
        'Zed',
    ];

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    public function update(array $composerJson, SplFileInfo $composerJsonFile)
    {
        $composerJson = $this->updateAutoloadForTests($composerJson, $composerJsonFile);

        return $composerJson;
    }

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    protected function updateAutoloadForTests(array $composerJson, SplFileInfo $composerJsonFile)
    {
        $testDirectoryKeys = $this->buildTestDirectoryKeys();
        $bundlePath = dirname($composerJsonFile->getPathname());

        foreach ($testDirectoryKeys as $testDirectoryKey) {
            $composerJson = $this->updateAutoload($composerJson, $testDirectoryKey, $bundlePath);
        }

        return $composerJson;
    }

    /**
     * @param array $composerJson
     * @param string $testDirectoryKey
     * @param string $bundlePath
     *
     * @return array
     */
    protected function updateAutoload(array $composerJson, $testDirectoryKey, $bundlePath)
    {
        $directoryName = $this->getDirectoryName($testDirectoryKey, $bundlePath);

        if ($this->directoryExists($directoryName)) {
            $composerJson = $this->addAutoloadDevPsr0($composerJson);
            $composerJson['autoload-dev']['psr-0'][$testDirectoryKey] = 'tests/';
        }

        if (isset($composerJson['autoload']['psr-0'][$testDirectoryKey])) {
            unset($composerJson['autoload']['psr-0'][$testDirectoryKey]);
        }

        return $composerJson;
    }

    /**
     * @param string $directory
     *
     * @return bool
     */
    protected function directoryExists($directory)
    {
        return (is_dir($directory));
    }

    /**
     * @return array
     */
    protected function buildTestDirectoryKeys()
    {
        $testDirectoryKeys = [];
        foreach ($this->testDirectoryKeys as $testDirectoryKey) {
            $testDirectoryKeys[] = $testDirectoryKey;
            foreach ($this->applications as $application) {
                $testDirectoryKeys[] = $application . $testDirectoryKey;
            }
        }

        return $testDirectoryKeys;
    }

    /**
     * @param string $testDirectoryKey
     * @param string $bundlePath
     *
     * @return string
     */
    protected function getDirectoryName($testDirectoryKey, $bundlePath)
    {
        $pathParts = [
            rtrim($bundlePath, DIRECTORY_SEPARATOR),
            'tests',
            $testDirectoryKey,
        ];

        return implode($pathParts, DIRECTORY_SEPARATOR);
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    protected function addAutoloadDevPsr0(array $composerJson)
    {
        if (!isset($composerJson['autoload-dev'])) {
            $composerJson['autoload-dev'] = [];
        }
        if (!isset($composerJson['autoload-dev']['psr-0'])) {
            $composerJson['autoload-dev']['psr-0'] = [];
        }

        return $composerJson;
    }
}
