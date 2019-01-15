<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\LocalizedUrlTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig;

class UrlGenerator implements UrlGeneratorInterface
{
    public const ROUTE_UNSUBSCRIBE = '/availability-notification/unsubscribe';

    public const PARAM_SUBSCRIPTION_KEY = 'subscriptionKey';

    /**
     * @var \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig $config
     */
    public function __construct(AvailabilityNotificationConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return string
     */
    public function createUnsubscriptionLink(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): string
    {
        $params = [static::PARAM_SUBSCRIPTION_KEY => $availabilitySubscriptionTransfer->getSubscriptionKey()];
        $unsubscriptionUrl = Url::generate(static::ROUTE_UNSUBSCRIBE, $params)->build();

        return $this->config->getBaseUrlYves() . $unsubscriptionUrl;
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedUrlTransfer $localizedUrlTransfer
     *
     * @return string
     */
    public function generateProductUrl(LocalizedUrlTransfer $localizedUrlTransfer): string
    {
        $yvesBaseUrl = $this->config->getBaseUrlYves();

        return $yvesBaseUrl . $localizedUrlTransfer->getUrl();
    }
}
