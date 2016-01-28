<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\Composer\Updater;

interface UpdaterInterface
{

    /**
     * @param array $composerJson
     *
     * @return array
     */
    public function update(array $composerJson);

}
