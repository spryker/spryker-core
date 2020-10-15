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
     * - Defines debug mode for transfer generation process.
     *
     * @api
     */
    public const DEBUG = 'TRANSFER:DEBUG';

    /**
     * Specification:
     * - Enables application-wide debug mode.
     *
     * @api
     *
     * @uses \Spryker\Shared\Application\ApplicationConstants::ENABLE_APPLICATION_DEBUG
     */
    public const ENABLE_APPLICATION_DEBUG = 'APPLICATION:ENABLE_APPLICATION_DEBUG';
}
