<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionFile;

use Spryker\Shared\SessionFile\SessionFileConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\SessionFile\SessionFileConfig getSharedConfig()
 */
class SessionFileConfig extends AbstractBundleConfig
{
    public const SESSION_HANDLER_FILE = 'file';

    /**
     * @return int
     */
    public function getSessionLifeTime(): int
    {
        return (int)$this->get(SessionFileConstants::ZED_SESSION_TIME_TO_LIVE, 0);
    }

    /**
     * @return string
     */
    public function getSessionHandlerFileSavePath(): string
    {
        return $this->get(SessionFileConstants::ZED_SESSION_FILE_PATH, '');
    }

    /**
     * @return string
     */
    public function getSessionHandlerFileName(): string
    {
        return $this->getSharedConfig()->getSessionHandlerFileName();
    }
}
