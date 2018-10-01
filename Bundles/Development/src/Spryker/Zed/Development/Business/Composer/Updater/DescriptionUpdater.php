<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use Symfony\Component\Finder\SplFileInfo;

class DescriptionUpdater implements UpdaterInterface
{
    public const KEY_DESCRIPTION = 'description';

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    public function update(array $composerJson, SplFileInfo $composerJsonFile)
    {
        $moduleName = $composerJsonFile->getRelativePath();
        $composerJson[static::KEY_DESCRIPTION] = $moduleName . ' module';

        return $composerJson;
    }
}
