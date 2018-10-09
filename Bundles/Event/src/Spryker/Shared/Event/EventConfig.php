<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Event;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class EventConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Routing key for forwarding message to retry queue
     *
     * @api
     */
    public const EVENT_ROUTING_KEY_RETRY = 'retry';

    /**
     * Specification:
     * - Routing key for forwarding message to error queue
     *
     * @api
     */
    public const EVENT_ROUTING_KEY_ERROR = 'error';
}
