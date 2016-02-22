<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Business\Composer\Updater;

class LicenseUpdater implements UpdaterInterface
{

    const KEY_LICENSE = 'license';

    /**
     * @var string
     */
    private $license;

    /**
     * @param string $license
     */
    public function __construct($license)
    {
        $this->license = $license;
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    public function update(array $composerJson)
    {
        $composerJson[self::KEY_LICENSE] = $this->license;

        return $composerJson;
    }

}
