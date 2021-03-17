<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AvailabilityNotificationsRestApi;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Symfony\Component\HttpFoundation\Response;

class AvailabilityNotificationsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_AVAILABILITY_NOTIFICATIONS = 'availability-notifications';
    public const RESOURCE_MY_AVAILABILITY_NOTIFICATIONS = 'my-availability-notifications';
    public const RESOURCE_CUSTOMERS = 'customers';

    public const RESPONSE_CODE_PRODUCT_NOT_FOUND = '4601';
    public const RESPONSE_CODE_SUBSCRIPTION_ALREADY_EXISTS = '4602';
    public const RESPONSE_CODE_SUBSCRIPTION_DOES_NOT_EXIST = '4603';
    public const RESPONSE_CODE_FAILED_TO_SUBSCRIBE = '4604';
    public const RESPONSE_CODE_FAILED_TO_UNSUBSCRIBE = '4605';
    public const RESPONSE_CODE_CUSTOMER_UNAUTHORIZED = '4606';

    /**
     * @uses \Spryker\Shared\AvailabilityNotification\AvailabilityNotificationConfig::MESSAGE_PRODUCT_NOT_FOUND
     */
    public const RESPONSE_DETAIL_PRODUCT_NOT_FOUND = 'Product not found.';
    /**
     * @uses \Spryker\Shared\AvailabilityNotification\AvailabilityNotificationConfig::MESSAGE_SUBSCRIPTION_ALREADY_EXISTS
     */
    public const RESPONSE_DETAIL_SUBSCRIPTION_ALREADY_EXISTS = 'Subscription already exists.';
    /**
     * @uses \Spryker\Shared\AvailabilityNotification\AvailabilityNotificationConfig::MESSAGE_SUBSCRIPTION_DOES_NOT_EXIST
     */
    public const RESPONSE_DETAIL_SUBSCRIPTION_DOES_NOT_EXIST = 'Subscription doesn\'t exist.';
    public const RESPONSE_DETAIL_FAILED_TO_SUBSCRIBE = 'Failed to subscribe.';
    public const RESPONSE_DETAIL_FAILED_TO_UNSUBSCRIBE = 'Failed to unsubscribe.';
    public const RESPONSE_DETAILS_CUSTOMER_UNAUTHORIZED = 'Unauthorized request.';

    /**
     * @api
     *
     * @return mixed[][]
     */
    public function getErrorIdentifierToRestErrorMapping(): array
    {
        return [
            static::RESPONSE_DETAIL_PRODUCT_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_PRODUCT_NOT_FOUND,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_PRODUCT_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
            ],
            static::RESPONSE_DETAIL_SUBSCRIPTION_ALREADY_EXISTS => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_SUBSCRIPTION_ALREADY_EXISTS,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_SUBSCRIPTION_ALREADY_EXISTS,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
            ],
            static::RESPONSE_DETAIL_SUBSCRIPTION_DOES_NOT_EXIST => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_SUBSCRIPTION_DOES_NOT_EXIST,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_SUBSCRIPTION_DOES_NOT_EXIST,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
            ],
            static::RESPONSE_DETAIL_FAILED_TO_SUBSCRIBE => $this->getDefaultSubscribeRestError(),
            static::RESPONSE_DETAIL_FAILED_TO_UNSUBSCRIBE => $this->getDefaultUnsubscribeRestError(),
            static::RESPONSE_DETAILS_CUSTOMER_UNAUTHORIZED => $this->getCustomerUnauthorizedRestError(),
        ];
    }

    /**
     * @api
     *
     * @return mixed[]
     */
    public function getDefaultSubscribeRestError(): array
    {
        return [
            RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_FAILED_TO_SUBSCRIBE,
            RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_FAILED_TO_SUBSCRIBE,
            RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
        ];
    }

    /**
     * @api
     *
     * @return mixed[]
     */
    public function getDefaultUnsubscribeRestError(): array
    {
        return [
            RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_FAILED_TO_UNSUBSCRIBE,
            RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_FAILED_TO_UNSUBSCRIBE,
            RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
        ];
    }

    /**
     * @api
     *
     * @return mixed[]
     */
    public function getCustomerUnauthorizedRestError(): array
    {
        return [
            RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CUSTOMER_UNAUTHORIZED,
            RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAILS_CUSTOMER_UNAUTHORIZED,
            RestErrorMessageTransfer::STATUS => Response::HTTP_FORBIDDEN,
        ];
    }
}
