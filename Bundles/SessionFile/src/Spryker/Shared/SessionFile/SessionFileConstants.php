<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionFile;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SessionFileConstants
{
    /**
     * Specification:
     * - Sets time to live for Zed sessions.
     *
     * @api
     */
    public const ZED_SESSION_TIME_TO_LIVE = 'SESSION_FILE:ZED_SESSION_TIME_TO_LIVE';

    /**
     * Specification:
     * - Sets file path for saving Zed sessions.
     *
     * @api
     */
    public const ZED_SESSION_FILE_PATH = 'SESSION_FILE:ZED_SESSION_FILE_PATH';

    /**
     * Specification:
     * - Sets time to live for Yves sessions.
     *
     * @api
     */
    public const YVES_SESSION_TIME_TO_LIVE = 'SESSION_FILE:YVES_SESSION_TIME_TO_LIVE';

    /**
     * Specification:
     * - Sets file path for saving Yves sessions.
     *
     * @api
     */
    public const YVES_SESSION_FILE_PATH = 'SESSION_FILE:YVES_SESSION_FILE_PATH';
}
