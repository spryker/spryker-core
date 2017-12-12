<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

interface ComposerUpdaterCompositeInterface extends UpdaterInterface
{
    /**
     * @param \Spryker\Zed\Development\Business\Composer\Updater\UpdaterInterface $updater
     *
     * @return $this
     */
    public function addUpdater(UpdaterInterface $updater);
}
