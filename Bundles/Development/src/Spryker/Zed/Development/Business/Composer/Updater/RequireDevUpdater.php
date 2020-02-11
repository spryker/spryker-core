<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use Symfony\Component\Finder\SplFileInfo;

class RequireDevUpdater implements UpdaterInterface
{
    public const KEY_REQUIRE_DEV = 'require-dev';
    public const PACKAGE_CODE_SNIFFER = 'spryker/code-sniffer';

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    public function update(array $composerJson, SplFileInfo $composerJsonFile): array
    {
        $path = pathinfo($composerJsonFile, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR;
        $composerJson = $this->assertCodeSniffer($path, $composerJson);

        return $composerJson;
    }

    /**
     * @param string $path
     * @param array $composerJson
     *
     * @return array
     */
    protected function assertCodeSniffer(string $path, array $composerJson): array
    {
        $requiresCodeSniffer = is_dir($path . 'src');

        if ($requiresCodeSniffer && isset($composerJson[static::KEY_REQUIRE_DEV][self::PACKAGE_CODE_SNIFFER])) {
            return $composerJson;
        }

        if ($requiresCodeSniffer && !isset($composerJson[static::KEY_REQUIRE_DEV][self::PACKAGE_CODE_SNIFFER])) {
            $composerJson[static::KEY_REQUIRE_DEV][static::PACKAGE_CODE_SNIFFER] = '*';
        } elseif (!$requiresCodeSniffer) {
            unset($composerJson[static::KEY_REQUIRE_DEV][static::PACKAGE_CODE_SNIFFER]);
        }

        return $composerJson;
    }
}
