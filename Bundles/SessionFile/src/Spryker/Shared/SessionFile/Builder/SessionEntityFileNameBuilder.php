<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionFile\Builder;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;

class SessionEntityFileNameBuilder implements SessionEntityFileNameBuilderInterface
{
    /**
     * @var int
     */
    protected const ACTIVE_SESSION_DIRECTORY_PERMISSIONS = 0775;

    /**
     * @var string
     */
    protected string $activeSessionFilePath;

    /**
     * @param string $activeSessionFilePath
     */
    public function __construct(string $activeSessionFilePath)
    {
        $this->activeSessionFilePath = $activeSessionFilePath;
    }

    /**
     * @param \Generated\Shared\Transfer\SessionEntityRequestTransfer $sessionEntityRequestTransfer
     *
     * @return string
     */
    public function build(SessionEntityRequestTransfer $sessionEntityRequestTransfer): string
    {
        if (!is_dir($this->activeSessionFilePath)) {
            mkdir($this->activeSessionFilePath, static::ACTIVE_SESSION_DIRECTORY_PERMISSIONS, true);
        }

        return rtrim($this->activeSessionFilePath, DIRECTORY_SEPARATOR) .
            DIRECTORY_SEPARATOR .
            sprintf(
                'session:%s:%s',
                $sessionEntityRequestTransfer->getEntityTypeOrFail(),
                $sessionEntityRequestTransfer->getIdEntityOrFail(),
            );
    }
}
