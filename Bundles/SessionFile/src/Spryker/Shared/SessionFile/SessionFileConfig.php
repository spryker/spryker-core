<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionFile;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class SessionFileConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const SESSION_HANDLER_FILE = 'file';

    /**
     * @api
     *
     * @return string
     */
    public function getSessionHandlerFileName(): string
    {
        return static::SESSION_HANDLER_FILE;
    }

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
        return $this->get(
            SessionFileConstants::ACTIVE_SESSION_FILE_PATH,
            APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'session',
        );
    }
}
