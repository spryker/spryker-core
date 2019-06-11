<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SetupFrontend;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SetupFrontendConstants
{
    /**
     * Specification:
     * - Sets the command to build Yves assets.
     * - %store% will be replaced with current store.
     *
     * @api
     */
    public const YVES_BUILD_COMMAND = 'SETUP_FRONTEND:YVES_BUILD_COMMAND';
}
