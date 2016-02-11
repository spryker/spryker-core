<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\Composer\Updater;

class StabilityUpdater implements UpdaterInterface
{

    const KEY_MINIMUM_STABILITY = 'minimum-stability';
    const KEY_PREFER_STABLE = 'prefer-stable';

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
        $composerJson = $this->updateMinimumStability($composerJson);
        $composerJson = $this->updatePreferStable($composerJson);

        return $composerJson;
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    private function updateMinimumStability(array $composerJson)
    {
        $composerJson[self::KEY_MINIMUM_STABILITY] = $this->stability;

        return $composerJson;
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    private function updatePreferStable(array $composerJson)
    {
        $composerJson[self::KEY_PREFER_STABLE] = true;

        return $composerJson;
    }

}
