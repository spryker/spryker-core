<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Transfer;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface TransferConstants
{
    /**
     * Specification:
     * - If true, transfer generation is in debug mode.
     * - If false, transfer generation will run in normal mode.
     *
     * @api
     * @var string
     */
    public const IS_DEBUG_ENABLED = 'TRANSFER:IS_DEBUG_ENABLED';
}
