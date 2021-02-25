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

    public const RESPONSE_DETAIL_PRODUCT_NOT_FOUND = "Product not found.";
}
