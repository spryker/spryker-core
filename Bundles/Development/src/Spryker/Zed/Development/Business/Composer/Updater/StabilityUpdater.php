<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use Symfony\Component\Finder\SplFileInfo;

class StabilityUpdater implements UpdaterInterface
{
    public const KEY_MINIMUM_STABILITY = 'minimum-stability';
    public const KEY_PREFER_STABLE = 'prefer-stable';

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
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    public function update(array $composerJson, SplFileInfo $composerJsonFile)
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
        $composerJson[static::KEY_MINIMUM_STABILITY] = $this->stability;

        return $composerJson;
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    protected function updatePreferStable(array $composerJson)
    {
        $composerJson[static::KEY_PREFER_STABLE] = true;

        return $composerJson;
    }
}
