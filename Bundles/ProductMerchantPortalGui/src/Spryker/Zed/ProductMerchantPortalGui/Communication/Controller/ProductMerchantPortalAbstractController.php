<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

abstract class ProductMerchantPortalAbstractController extends AbstractController
{
    protected const RESPONSE_TYPE_SUCCESS = 'success';
    protected const RESPONSE_TYPE_ERROR = 'error';
    protected const RESPONSE_TYPE_REFRESH_TABLE = 'refresh_table';
    protected const RESPONSE_TYPE_CLOSE_OVERLAY = 'close_overlay';

    protected const RESPONSE_KEY_POST_ACTIONS = 'postActions';
    protected const RESPONSE_KEY_NOTIFICATIONS = 'notifications';
    protected const RESPONSE_KEY_TYPE = 'type';
    protected const RESPONSE_KEY_MESSAGE = 'message';

    protected const RESPONSE_MESSAGE_SUCCESS = 'The Product is saved.';
    protected const RESPONSE_MESSAGE_ERROR = 'Please resolve all errors.';

    /**
     * @param mixed[] $responseData
     *
     * @return mixed[]
     */
    protected function addSuccessResponseDataToResponse(array $responseData): array
    {
        $responseData[static::RESPONSE_KEY_POST_ACTIONS] = [
            [
                static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_CLOSE_OVERLAY,
            ],
            [
                static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_REFRESH_TABLE,
            ],
        ];
        $responseData[static::RESPONSE_KEY_NOTIFICATIONS] = [[
            static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_SUCCESS,
            static::RESPONSE_KEY_MESSAGE => static::RESPONSE_MESSAGE_SUCCESS,
        ]];

        return $responseData;
    }

    /**
     * @param mixed[] $responseData
     *
     * @return mixed[]
     */
    protected function addErrorResponseDataToResponse(array $responseData): array
    {
        $responseData[static::RESPONSE_KEY_NOTIFICATIONS][] = [
            static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_ERROR,
            static::RESPONSE_KEY_MESSAGE => static::RESPONSE_MESSAGE_ERROR,
        ];

        return $responseData;
    }
}
