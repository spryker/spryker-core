<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Publisher;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class PublisherConfig extends AbstractBundleConfig
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
}
