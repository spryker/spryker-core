<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use Symfony\Component\Finder\SplFileInfo;

class LicenseUpdater implements UpdaterInterface
{
    const KEY_LICENSE = 'license';

    /**
     * @var string
     */
    protected $license;

    /**
     * @param string $license
     */
    public function __construct($license)
    {
        $this->license = $license;
    }

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    public function update(array $composerJson, SplFileInfo $composerJsonFile)
    {
        $composerJson[static::KEY_LICENSE] = $this->license;

        return $composerJson;
    }
}
