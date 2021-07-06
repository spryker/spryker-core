<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Response;

use LogicException;

class ResponseBuilder
{
    public const POST_ACTION_REFRESH_DRAWER = 'refresh_drawer';
    public const POST_ACTION_CLOSE_OVERLAY = 'close_overlay';
    public const POST_ACTION_REFRESH_TABLE = 'refresh_table';

    public const RESPONSE_TYPE_SUCCESS = 'success';
    public const RESPONSE_TYPE_ERROR = 'error';

    protected const RESPONSE_MESSAGE_SUCCESS = 'Success!';
    protected const RESPONSE_MESSAGE_ERROR = 'Something went wrong, please try again.';

    protected const RESPONSE_KEY_POST_ACTIONS = 'postActions';
    protected const RESPONSE_KEY_NOTIFICATIONS = 'notifications';
    protected const RESPONSE_KEY_TYPE = 'type';
    protected const RESPONSE_KEY_MESSAGE = 'message';

    protected const POST_ACTIONS = [
        self::POST_ACTION_REFRESH_DRAWER,
        self::POST_ACTION_CLOSE_OVERLAY,
        self::POST_ACTION_REFRESH_TABLE,
    ];

    protected const RESPONSE_TYPES = [
        self::RESPONSE_TYPE_SUCCESS,
        self::RESPONSE_TYPE_ERROR,
    ];

    /**
     * @var array
     */
    protected $actions = [];

    /**
     * @var array
     */
    protected $notifications = [];

    /**
     * @param string $responseType
     * @param string $message
     *
     * @throws \LogicException
     *
     * @return void
     */
    public function addNotification(string $responseType, string $message): void
    {
        if (!in_array($responseType, static::RESPONSE_TYPES)) {
            throw new LogicException(sprintf('Response type must be one of: %s', implode(',', static::RESPONSE_TYPES)));
        }

        $this->notifications[] = [
            static::RESPONSE_KEY_TYPE => $responseType,
            static::RESPONSE_KEY_MESSAGE => $message,
        ];
    }

    /**
     * @param string $postActionType
     *
     * @throws \LogicException
     *
     * @return void
     */
    public function addAction(string $postActionType): void
    {
        if (!in_array($postActionType, static::POST_ACTIONS)) {
            throw new LogicException(sprintf('Post Action must be one of: %s', implode(',', static::POST_ACTIONS)));
        }

        $this->actions[] = [
            static::RESPONSE_KEY_TYPE => $postActionType,
        ];
    }

    /**
     * @return array
     */
    public function build(): array
    {
        return [
            static::RESPONSE_KEY_POST_ACTIONS => $this->actions,
            static::RESPONSE_KEY_NOTIFICATIONS => $this->notifications,
        ];
    }
}
