<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Development\DevelopmentConstants;
use Symfony\Component\Finder\SplFileInfo;

class BranchAliasUpdater implements UpdaterInterface
{
    public const KEY_EXTRA = 'extra';
    public const KEY_BRANCH_ALIAS = 'branch-alias';
    public const KEY_MASTER_BRANCH = 'dev-master';

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    public function update(array $composerJson, SplFileInfo $composerJsonFile)
    {
        if (!Config::hasValue(DevelopmentConstants::COMPOSER_BRANCH_ALIAS)) {
            return $composerJson;
        }
        $alias = Config::get(DevelopmentConstants::COMPOSER_BRANCH_ALIAS);

        $composerJson[static::KEY_EXTRA] = [
          static::KEY_BRANCH_ALIAS => [
              static::KEY_MASTER_BRANCH => $alias,
          ],
        ];

        return $composerJson;
    }
}
