<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
