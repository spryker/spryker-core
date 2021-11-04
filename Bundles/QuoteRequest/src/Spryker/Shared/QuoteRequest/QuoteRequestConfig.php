<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\QuoteRequest;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class QuoteRequestConfig extends AbstractSharedConfig
{
    /**
     * @var string
     */
    public const STATUS_DRAFT = 'draft';

    /**
     * @var string
     */
    public const STATUS_WAITING = 'waiting';

    /**
     * @var string
     */
    public const STATUS_IN_PROGRESS = 'in-progress';

    /**
     * @var string
     */
    public const STATUS_READY = 'ready';

    /**
     * @var string
     */
    public const STATUS_CLOSED = 'closed';

    /**
     * @var string
     */
    public const STATUS_CANCELED = 'canceled';

    /**
     * @var int
     */
    protected const INITIAL_VERSION_NUMBER = 1;

    /**
     * @api
     *
     * @return int
     */
    public function getInitialVersion(): int
    {
        return static::INITIAL_VERSION_NUMBER;
    }

    /**
     * @api
     *
     * @return array<string>
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
     * @api
     *
     * @return array<string>
     */
    public function getEditableStatuses(): array
    {
        return [
            static::STATUS_DRAFT,
        ];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getRevisableStatuses(): array
    {
        return [
            static::STATUS_READY,
        ];
    }

    /**
     * @api
     *
     * @return array<string>
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
     * @api
     *
     * @return array<string>
     */
    public function getUserEditableStatuses(): array
    {
        return [
            static::STATUS_IN_PROGRESS,
        ];
    }

    /**
     * @api
     *
     * @return array<string>
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
