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
     *
     * @var string
     */
    public const PROCESS_LOCATION = 'PROCESS_LOCATION';

    /**
     * Specification:
     * - Defines which of defined processes will be active
     *
     * @api
     *
     * @var string
     */
    public const ACTIVE_PROCESSES = 'ACTIVE_PROCESSES';

    /**
     * Specification:
     * - Defines if OMS transition log is enabled.
     *
     * @api
     *
     * @var string
     */
    public const ENABLE_OMS_TRANSITION_LOG = 'OMS:ENABLE_OMS_TRANSITION_LOG';

    /**
     * Specification:
     * - Defines where to store cached processes.
     *
     * @api
     *
     * @var string
     */
    public const PROCESS_CACHE_PATH = 'OMS:PROCESS_CACHE_PATH';
}
