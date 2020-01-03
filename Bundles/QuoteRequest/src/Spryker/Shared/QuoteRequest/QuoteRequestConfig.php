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

    protected const INITIAL_VERSION_NUMBER = 1;

    /**
     * @return int
     */
    public function getInitialVersion(): int
    {
        return static::INITIAL_VERSION_NUMBER;
    }

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

    /**
     * @return string[]
     */
    public function getEditableStatuses(): array
    {
        return [
            static::STATUS_DRAFT,
        ];
    }

    /**
     * @return string[]
     */
    public function getRevisableStatuses(): array
    {
        return [
            static::STATUS_READY,
        ];
    }

    /**
     * @return string[]
     */
    public function getUserCancelableStatuses(): array
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
    public function getUserEditableStatuses(): array
    {
        return [
            static::STATUS_IN_PROGRESS,
        ];
    }

    /**
     * @return string[]
     */
    public function getUserRevisableStatuses(): array
    {
        return [
            static::STATUS_DRAFT,
            static::STATUS_WAITING,
            static::STATUS_READY,
        ];
    }
}
