<?php

namespace Spryker\Glue\AvailabilityNotificationsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class AvailabilityNotificationsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_AVAILABILITY_NOTIFICATIONS = "availability-notifications";

    /**
     * @see \Spryker\Glue\AvailabilityNotificationsRestApi\Controller\AvailabilityNotificationsResourceController
     */
    public const CONTROLLER_AVAILABILITY_NOTIFICATIONS = "availability-notifications-resource";

    public const RESPONSE_CODE_PRODUCT_NOT_FOUND = "4601";
    public const RESPONSE_CODE_SUBSCRIPTION_ALREADY_EXISTS = "4602";
    public const RESPONSE_CODE_SUBSCRIPTION_NOT_EXISTS = "4603";
    public const RESPONSE_CODE_SOMETHING_WENT_WRONG = "4604";

    public const RESPONSE_DETAIL_PRODUCT_NOT_FOUND = "Product not found.";
    public const RESPONSE_DETAIL_SUBSCRIPTION_ALREADY_EXISTS = "Subscription already exists"; // TODO create according constant in zed "Subscription already exists.";
    public const RESPONSE_DETAIL_SUBSCRIPTION_NOT_EXISTS = "Subscription doesn't exist"; //TODO create according constant in zed "Subscription doesn't exists.";
    public const RESPONSE_DETAIL_SOMETHING_WENT_WRONG = "Something went wrong.";
}
