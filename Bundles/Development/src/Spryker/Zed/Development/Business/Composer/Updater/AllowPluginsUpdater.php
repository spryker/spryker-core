<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use stdClass;
use Symfony\Component\Finder\SplFileInfo;

class AllowPluginsUpdater implements UpdaterInterface
{
    /**
     * @var string
     */
    public const KEY_CONFIG = 'config';

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    public function update(array $composerJson, SplFileInfo $composerJsonFile): array
    {
        $path = pathinfo($composerJsonFile, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR;
        $composerJson = $this->assertAllowPluginsConfig($path, $composerJson);

        if (isset($composerJson[static::KEY_CONFIG]) && empty($composerJson[static::KEY_CONFIG])) {
            $composerJson[static::KEY_CONFIG] = new stdClass();
        }

        return $composerJson;
    }

    /**
     * @param string $path
     * @param array $jsonArray
     *
     * @return array
     */
    protected function assertAllowPluginsConfig(string $path, array $jsonArray): array
    {
        $requiresCodeSniffer = is_dir($path . 'src');
        if (!$requiresCodeSniffer) {
            unset($jsonArray['config']['allow-plugins']);

            return $jsonArray;
        }

        $jsonArray['config']['allow-plugins'] = [
            'dealerdirect/phpcodesniffer-composer-installer' => true,
        ];

        return $jsonArray;
    }
}
