<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\QuoteRequest;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class QuoteRequestConfig extends AbstractSharedConfig
{
    public const STATUS_WAITING = 'Waiting';
    public const STATUS_IN_PROGRESS = 'In progress';
    public const STATUS_READY = 'Ready';
    public const STATUS_CLOSED = 'Closed';
    public const STATUS_CANCELED = 'Canceled';

    public const INITIAL_VERSION_NUMBER = 1;
}
