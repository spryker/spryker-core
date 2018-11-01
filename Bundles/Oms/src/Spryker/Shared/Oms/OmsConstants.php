<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Oms;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface OmsConstants
{
    /**
     * Specification:
     * - Defines paths to OMS schemas
     *
     * @api
     */
    public const PROCESS_LOCATION = 'PROCESS_LOCATION';

    /**
     * Specification:
     * - Defines which of defined processes will be active
     *
     * @api
     */
    public const ACTIVE_PROCESSES = 'ACTIVE_PROCESSES';
}
