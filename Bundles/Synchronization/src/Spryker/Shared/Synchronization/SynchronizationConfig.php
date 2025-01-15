<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Synchronization;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class SynchronizationConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     * - Routing key for failed messages
     *
     * @api
     *
     * @var string
     */
    public const MESSAGE_ROUTING_KEY_ERROR = 'error';
}
