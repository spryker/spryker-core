<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AvailabilityNotificationsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class AvailabilityNotificationsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_AVAILABILITY_NOTIFICATIONS = 'availability-notifications';

    public const RESPONSE_CODE_PRODUCT_NOT_FOUND = '4601';
    public const RESPONSE_CODE_SUBSCRIPTION_ALREADY_EXISTS = '4602';
    public const RESPONSE_CODE_SUBSCRIPTION_DOES_NOT_EXIST = '4603';
    public const RESPONSE_CODE_SOMETHING_WENT_WRONG = '4604'; // todo make 2 separate errors: failed to subscribe and failed to unsubscribe

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
    public const RESPONSE_DETAIL_SUBSCRIPTION_DOES_NOT_EXIST = "Subscription doesn't exist.";
    public const RESPONSE_DETAIL_SOMETHING_WENT_WRONG = 'Something went wrong.';
}
