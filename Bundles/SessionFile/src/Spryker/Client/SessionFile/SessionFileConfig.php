<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SessionFile;

use Spryker\Client\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\SessionFile\SessionFileConfig getSharedConfig()
 */
class SessionFileConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns file path for saving active session IDs.
     *
     * @api
     *
     * @return string
     */
    public function getActiveSessionFilePath(): string
    {
        return $this->getSharedConfig()->getActiveSessionFilePath();
    }
}
