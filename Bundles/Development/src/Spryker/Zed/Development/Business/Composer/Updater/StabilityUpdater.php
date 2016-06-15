<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

class StabilityUpdater implements UpdaterInterface
{

    const KEY_MINIMUM_STABILITY = 'minimum-stability';
    const KEY_PREFER_STABLE = 'prefer-stable';

    /**
     * @var string
     */
    protected $stability;

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
    protected function updateMinimumStability(array $composerJson)
    {
        $composerJson[self::KEY_MINIMUM_STABILITY] = $this->stability;

        return $composerJson;
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    protected function updatePreferStable(array $composerJson)
    {
        $composerJson[self::KEY_PREFER_STABLE] = true;

        return $composerJson;
    }

}
