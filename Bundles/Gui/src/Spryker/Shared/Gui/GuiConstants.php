<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Gui;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface GuiConstants
{
    /**
     * Specification:
     * - Returns the base url path to be used to build Zed assets.
     *
     * @example '/assets/'
     *
     * @api
     */
    public const ZED_ASSETS_PATH = 'GUI:ZED_ASSETS_PATH';
}
