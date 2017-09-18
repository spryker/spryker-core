<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Event;

interface EventConstants
{

    /**
     * Specification:
     * - Log file location for logging all events in system (path to file)
     *
     * @api
     */
    const LOG_FILE_PATH = 'EVENT_LOG_FILE_PATH';

    /**
     * Specification:
     * - Is logging activated for events (true|false)
     *
     * @api
     */
    const LOGGER_ACTIVE = 'LOGGER_ACTIVE';

    /**
     * Specification:
     * - Queue name as used when with asynchronous event handling
     *
     * @api
     */
    const EVENT_QUEUE = 'event';

    /**
     * Specification:
     * - Error queue name as used when with asynchronous event handling
     *
     * @api
     */
    const EVENT_ERROR_QUEUE = 'event.error';

}
