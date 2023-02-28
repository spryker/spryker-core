<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\LocalizedUrlTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig;
use Spryker\Zed\AvailabilityNotification\Business\Resolver\BaseUrlGetStrategyResolverInterface;

class UrlGenerator implements UrlGeneratorInterface
{
    /**
     * @var string
     */
    public const PARAM_SUBSCRIPTION_KEY = 'subscriptionKey';

    /**
     * @var \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig
     */
    protected AvailabilityNotificationConfig $availabilityNotificationConfig;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Resolver\BaseUrlGetStrategyResolverInterface
     */
    protected BaseUrlGetStrategyResolverInterface $baseUrlGetStrategyResolver;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig $availabilityNotificationConfig
     * @param \Spryker\Zed\AvailabilityNotification\Business\Resolver\BaseUrlGetStrategyResolverInterface $baseUrlGetStrategyResolver
     */
    public function __construct(
        AvailabilityNotificationConfig $availabilityNotificationConfig,
        BaseUrlGetStrategyResolverInterface $baseUrlGetStrategyResolver
    ) {
        $this->availabilityNotificationConfig = $availabilityNotificationConfig;
        $this->baseUrlGetStrategyResolver = $baseUrlGetStrategyResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return string
     */
    public function createUnsubscriptionLink(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): string
    {
        $localeName = $availabilityNotificationSubscriptionTransfer->getLocaleOrFail()->getLocaleNameOrFail();
        $unsubscribeUri = sprintf(
            $this->availabilityNotificationConfig->getUnsubscribeUri(),
            $this->getLanguageFromLocale($localeName),
            $availabilityNotificationSubscriptionTransfer->getSubscriptionKey(),
        );

        return sprintf(
            '%s%s',
            $this->getBaseUrl($availabilityNotificationSubscriptionTransfer->getStore()),
            $unsubscribeUri,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedUrlTransfer $localizedUrlTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return string
     */
    public function generateProductUrl(
        LocalizedUrlTransfer $localizedUrlTransfer,
        ?StoreTransfer $storeTransfer = null
    ): string {
        return sprintf(
            '%s%s',
            $this->getBaseUrl($storeTransfer),
            (string)$localizedUrlTransfer->getUrl(),
        );
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

        return current($splitLocale);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return string
     */
    protected function getBaseUrl(?StoreTransfer $storeTransfer = null): string
    {
        $baseUrlGetStrategy = $this->baseUrlGetStrategyResolver->resolveBaseUrlGetStrategy($storeTransfer);
        if ($baseUrlGetStrategy !== null) {
            return $baseUrlGetStrategy->getBaseUrl($storeTransfer);
        }

        return $this->availabilityNotificationConfig->getBaseUrlYves();
    }
}
