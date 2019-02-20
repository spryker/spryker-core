<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\QuoteRequest;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class QuoteRequestConfig extends AbstractSharedConfig
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_WAITING = 'waiting';
    public const STATUS_IN_PROGRESS = 'in-progress';
    public const STATUS_READY = 'ready';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_CANCELED = 'canceled';

    public const INITIAL_VERSION_NUMBER = 1;

    /**
     * @return string[]
     */
    public function getCancelableStatuses(): array
    {
        return [
            static::STATUS_DRAFT,
            static::STATUS_WAITING,
            static::STATUS_READY,
        ];
    }
}
