<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Publisher;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class PublisherConfig extends AbstractSharedConfig
{
    /**
     * Defines queue name as used when with asynchronous event handling.
     */
    public const PUBLISH_QUEUE = 'publish';

    /**
     * Defines error queue name as used when with asynchronous event handling
     */
    public const PUBLISH_ERROR_QUEUE = 'publish.error';

    /**
     * Defines retry queue name as used when with asynchronous event handling.
     */
    public const PUBLISH_RETRY_QUEUE = 'publish.retry';

    /**
     * Defines routing key for forwarding message to retry queue.
     */
    public const PUBLISH_ROUTING_KEY_RETRY = 'retry';

    /**
     * Defines routing key for forwarding message to error queue.
     */
    public const PUBLISH_ROUTING_KEY_ERROR = 'error';
}
