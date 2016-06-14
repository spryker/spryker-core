<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Development\DevelopmentConstants;

class BranchAliasUpdater implements UpdaterInterface
{

    const KEY_EXTRA = 'extra';
    const KEY_BRANCH_ALIAS = 'branch-alias';
    const KEY_MASTER_BRANCH = 'dev-master';

    /**
     * @param array $composerJson
     *
     * @return array
     */
    public function update(array $composerJson)
    {
        if (!Config::hasValue(DevelopmentConstants::COMPOSER_BRANCH_ALIAS)) {
            return $composerJson;
        }
        $alias = Config::get(DevelopmentConstants::COMPOSER_BRANCH_ALIAS);

        $composerJson[self::KEY_EXTRA] = [
          self::KEY_BRANCH_ALIAS => [
              self::KEY_MASTER_BRANCH => $alias,
          ],
        ];

        return $composerJson;
    }

}
