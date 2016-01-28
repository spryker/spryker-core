<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\Composer\Updater;

class MinimumStabilityUpdater implements UpdaterInterface
{

    const KEY_MINIMUM_STABILITY = 'minimum-stability';

    /**
     * @var string
     */
    private $stability;

    /**
     * @param string $stability
     */
    public function __construct($stability)
    {
        $this->stability = $stability;
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    public function update(array $composerJson)
    {
        $composerJson[self::KEY_MINIMUM_STABILITY] = $this->stability;

        return $composerJson;
    }

}
