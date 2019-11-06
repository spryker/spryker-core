<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Publisher;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class PublisherConfig extends AbstractSharedConfig
{
    public const PUBLISHER_QUEUE_NAME = 'publisher';
    public const PUBLISHER_ERROR_QUEUE_NAME = 'publisher.error';
    public const PUBLISHER_RETRY_QUEUE_NAME = 'publisher.retry';
    public const PUBLISHER_ERROR_ROUTING_KEY = 'error';
    public const PUBLISHER_RETRY_ROUTING_KEY = 'retry';
}
