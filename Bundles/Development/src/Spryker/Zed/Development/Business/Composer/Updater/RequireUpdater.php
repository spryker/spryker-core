<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use Symfony\Component\Finder\SplFileInfo;

class RequireUpdater implements UpdaterInterface
{
    public const KEY_REQUIRE = 'require';
    public const KEY_REQUIRE_PHP = 'php';
    public const PHP_MINIMUM = '>=7.1';

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    public function update(array $composerJson, SplFileInfo $composerJsonFile)
    {
        $composerJson = $this->requirePhpVersion($composerJson);

        return $composerJson;
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    protected function requirePhpVersion(array $composerJson)
    {
        if (isset($composerJson[static::KEY_REQUIRE][static::KEY_REQUIRE_PHP])) {
            return $composerJson;
        }

        $composerJson[static::KEY_REQUIRE][static::KEY_REQUIRE_PHP] = static::PHP_MINIMUM;

        return $composerJson;
    }
}
