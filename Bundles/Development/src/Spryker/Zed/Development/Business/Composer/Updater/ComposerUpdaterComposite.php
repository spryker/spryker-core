<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use Symfony\Component\Finder\SplFileInfo;

class ComposerUpdaterComposite implements ComposerUpdaterCompositeInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\Composer\Updater\UpdaterInterface[]
     */
    protected $updater;

    /**
     * @param \Spryker\Zed\Development\Business\Composer\Updater\UpdaterInterface $updater
     *
     * @return $this
     */
    public function addUpdater(UpdaterInterface $updater)
    {
        $this->updater[] = $updater;

        return $this;
    }

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    public function update(array $composerJson, SplFileInfo $composerJsonFile): array
    {
        foreach ($this->updater as $updater) {
            $composerJson = $updater->update($composerJson, $composerJsonFile);
        }

        return $composerJson;
    }
}
