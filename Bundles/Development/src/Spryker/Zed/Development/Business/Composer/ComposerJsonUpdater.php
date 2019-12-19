<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer;

use RuntimeException;
use Spryker\Zed\Development\Business\Composer\Updater\UpdaterInterface;
use Spryker\Zed\Development\Business\Exception\DependencyTree\InvalidComposerJsonException;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\Word\CamelCaseToDash;

class ComposerJsonUpdater implements ComposerJsonUpdaterInterface
{
    public const REPLACE_4_WITH_2_SPACES = '/^(  +?)\\1(?=[^ ])/m';
    public const KEY_REQUIRE = 'require';
    public const KEY_REQUIRE_DEV = 'require-dev';

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
     * @param \Generated\Shared\Transfer\ModuleTransfer[] $moduleTransferCollection
     * @param bool $dryRun
     *
     * @return array
     */
    public function update(array $moduleTransferCollection, $dryRun = false)
    {
        $processed = [];

        foreach ($moduleTransferCollection as $moduleTransfer) {
            $moduleKey = implode('.', [$moduleTransfer->getOrganization()->getName(), $moduleTransfer->getName()]);
            $composerJsonFile = $this->finder->findByModule($moduleTransfer);

            if (!$composerJsonFile) {
                continue;
            }

            $processed[$moduleKey] = $this->updateComposerJsonFile($composerJsonFile, $dryRun);
        }

        return $processed;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     * @param bool $dryRun
     *
     * @return bool
     */
    protected function updateComposerJsonFile(SplFileInfo $composerJsonFile, $dryRun = false)
    {
        $composerJson = $composerJsonFile->getContents();
        $composerJsonArray = json_decode($composerJson, true);

        $this->assertCorrectName($composerJsonArray['name'], $composerJsonFile);

        $composerJsonArray = $this->updater->update($composerJsonArray, $composerJsonFile);
        $composerJsonArray = $this->clean($composerJsonArray);
        $composerJsonArray = $this->order($composerJsonArray);

        $modifiedComposerJson = json_encode($composerJsonArray, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        $modifiedComposerJson = preg_replace(static::REPLACE_4_WITH_2_SPACES, '$1', $modifiedComposerJson) . PHP_EOL;

        if ($modifiedComposerJson === $composerJson) {
            return false;
        }

        if (!$dryRun) {
            file_put_contents($composerJsonFile->getPathname(), $modifiedComposerJson);
        }

        return true;
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    protected function clean(array $composerJson)
    {
        if (!empty($composerJson[static::KEY_REQUIRE])) {
            ksort($composerJson[static::KEY_REQUIRE]);
        } elseif (isset($composerJson[static::KEY_REQUIRE])) {
            unset($composerJson[static::KEY_REQUIRE]);
        }

        if (!empty($composerJson[static::KEY_REQUIRE_DEV])) {
            ksort($composerJson[static::KEY_REQUIRE_DEV]);
        } elseif (isset($composerJson[static::KEY_REQUIRE_DEV])) {
            unset($composerJson[static::KEY_REQUIRE_DEV]);
        }

        $composerJson['config']['sort-packages'] = true;

        return $composerJson;
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    protected function order(array $composerJson)
    {
        $map = [
            'name',
            'type',
            'description',
            'homepage',
            'license',
            'require',
            'require-dev',
            'suggest',
            'autoload',
            'autoload-dev',
            'minimum-stability',
            'prefer-stable',
            'scripts',
            'repositories',
            'extra',
            'config',
        ];

        $callable = function ($a, $b) use ($map) {
            $keyA = in_array($a, $map) ? array_search($a, $map) : 999;
            $keyB = in_array($b, $map) ? array_search($b, $map) : 999;

            if ($keyA === $keyB) {
                return 0;
            }

            return $keyA > $keyB;
        };

        uksort($composerJson, $callable);

        return $composerJson;
    }

    /**
     * @param string $composerName
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function assertCorrectName(string $composerName, SplFileInfo $composerJsonFile)
    {
        $filter = new CamelCaseToDash();
        $moduleName = mb_strtolower($filter->filter(basename($composerJsonFile->getPath())));
        $organization = $this->getOrganizationFromComposerJsonFile($composerJsonFile);
        $expected = $organization . '/' . $moduleName;

        if ($composerName !== $expected) {
            throw new RuntimeException(sprintf('Invalid composer name, expected %s, got %s', $expected, $composerName));
        }
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @throws \Spryker\Zed\Development\Business\Exception\DependencyTree\InvalidComposerJsonException
     *
     * @return mixed
     */
    protected function getOrganizationFromComposerJsonFile(SplFileInfo $composerJsonFile)
    {
        if (preg_match('/vendor\/spryker\/([a-z_-]+)\/Bundles\/\w+\/composer.json$/', $composerJsonFile->getRealPath(), $matches)) {
            return $matches[1];
        }

        if (preg_match('/vendor\/([a-z_-]+)\/[a-z_-]+\/composer.json$/', $composerJsonFile->getRealPath(), $matches)) {
            return $matches[1];
        }

        throw new InvalidComposerJsonException(sprintf(
            'Unable to locate organization name from %s.',
            $composerJsonFile->getRealPath()
        ));
    }
}
