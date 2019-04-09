<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\QuoteRequestAgent;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class QuoteRequestAgentConfig extends AbstractSharedConfig
{
    /**
     * @see \Spryker\Shared\QuoteRequest\QuoteRequestConfig::STATUS_WAITING
     */
    public const STATUS_WAITING = 'waiting';

    /**
     * @see \Spryker\Shared\QuoteRequest\QuoteRequestConfig::STATUS_READY
     */
    public const STATUS_READY = 'ready';

    /**
     * @see \Spryker\Shared\QuoteRequest\QuoteRequestConfig::STATUS_DRAFT
     */
    public const STATUS_DRAFT = 'draft';

    /**
     * @see \Spryker\Shared\QuoteRequest\QuoteRequestConfig::STATUS_CANCELED
     */
    public const STATUS_CANCELED = 'canceled';

    /**
     * @see \Spryker\Shared\QuoteRequest\QuoteRequestConfig::STATUS_IN_PROGRESS
     */
    public const STATUS_IN_PROGRESS = 'in-progress';

    /**
     * @see \Spryker\Shared\QuoteRequest\QuoteRequestConfig::STATUS_CLOSED
     */
    public const STATUS_CLOSED = 'closed';

    /**
     * @return string[]
     */
    public function getCancelableStatuses(): array
    {
        return [
            static::STATUS_DRAFT,
            static::STATUS_WAITING,
            static::STATUS_IN_PROGRESS,
            static::STATUS_READY,
        ];
    }

    /**
     * @return string[]
     */
    public function getRevisableStatuses(): array
    {
        return [
            static::STATUS_DRAFT,
            static::STATUS_WAITING,
            static::STATUS_READY,
        ];
    }
}
