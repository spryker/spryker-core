<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\Composer\Updater;

class ComposerUpdaterComposite implements UpdaterInterface
{

    /**
     * @var \Spryker\Zed\Maintenance\Business\Composer\Updater\UpdaterInterface[]
     */
    private $updater;

    /**
     * @param \Spryker\Zed\Maintenance\Business\Composer\Updater\UpdaterInterface $updater
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
     *
     * @return array
     */
    public function update(array $composerJson)
    {
        foreach ($this->updater as $updater) {
            $composerJson = $updater->update($composerJson);
        }

        return $composerJson;
    }

}
