<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class LicenseUpdater implements UpdaterInterface
{
    protected const KEY_LICENSE = 'license';

    protected const LICENSE_TYPE_MIT = 'MIT';

    protected const LICENSE_TYPE_PROPRIETARY = 'proprietary';

    protected const MIT_LICENSE = 'The MIT License (MIT)';

    protected const LICENSE_FILE_DEPTH = 0;

    protected const LICENSE_FILE_NAME = 'LICENSE';

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    public function update(array $composerJson, SplFileInfo $composerJsonFile)
    {
        $modulePath = dirname($composerJsonFile->getPathname());
        $license = static::LICENSE_TYPE_PROPRIETARY;

        $isMITLicense = (new Finder())->files()
            ->in($modulePath)->depth(static::LICENSE_FILE_DEPTH)
            ->name(static::LICENSE_FILE_NAME)->contains(static::MIT_LICENSE)
            ->hasResults();

        if ($isMITLicense) {
            $license = static::LICENSE_TYPE_MIT;
        }

        $composerJson[static::KEY_LICENSE] = $license;

        return $composerJson;
    }
}
