<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\LocalizedUrlTransfer;
use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig;

class UrlGenerator implements UrlGeneratorInterface
{
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
        $yvesBaseUrl = $this->config->getBaseUrlYves();

        if ($yvesBaseUrl === null) {
            return '';
        }

        $localeName = $availabilitySubscriptionTransfer->getLocale()->getLocaleName();
        $locale = $this->getLanguageFromLocale($localeName);

        return $yvesBaseUrl . $locale . $this->config->getUnsubscribeRoute() . $availabilitySubscriptionTransfer->getSubscriptionKey();
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedUrlTransfer $localizedUrlTransfer
     *
     * @return string
     */
    public function generateProductUrl(LocalizedUrlTransfer $localizedUrlTransfer): string
    {
        $yvesBaseUrl = $this->config->getBaseUrlYves();

        if ($yvesBaseUrl === null) {
            return '';
        }

        return $yvesBaseUrl . $localizedUrlTransfer->getUrl();
    }

    /**
     * @param string $locale string The locale, e.g. 'de_DE'
     *
     * @return string The language, e.g. 'de'
     */
    protected function getLanguageFromLocale(string $locale): string
    {
        $splitLocale = explode('_', $locale);

        if (current($splitLocale) === false) {
            return '';
        }

        return '/' . current($splitLocale);
    }
}
