<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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
