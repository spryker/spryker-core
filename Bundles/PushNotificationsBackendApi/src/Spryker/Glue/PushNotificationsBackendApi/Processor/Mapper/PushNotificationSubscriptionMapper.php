<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PushNotificationGroupsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\PushNotificationGroupTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\PushNotificationUserTransfer;

class PushNotificationSubscriptionMapper implements PushNotificationSubscriptionMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer
     */
    public function mapPushNotificationSubscriptionTransferToPushNotificationSubscriptionsBackendApiAttributesTransfer(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer,
        PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer
    ): PushNotificationSubscriptionsBackendApiAttributesTransfer {
        $pushNotificationSubscriptionsBackendApiAttributesTransfer->fromArray(
            $pushNotificationSubscriptionTransfer->toArray(),
            true,
        );

        if ($pushNotificationSubscriptionTransfer->getLocale()) {
            $pushNotificationSubscriptionsBackendApiAttributesTransfer
                ->setLocaleName($pushNotificationSubscriptionTransfer->getLocaleOrFail()->getLocaleName());
        }

        $pushNotificationProviderName = $pushNotificationSubscriptionTransfer->getProviderOrFail()->getNameOrFail();
        $pushNotificationGroupsBackendApiAttributesTransfer = (new PushNotificationGroupsBackendApiAttributesTransfer())->fromArray(
            $pushNotificationSubscriptionTransfer->getGroupOrFail()->toArray(),
            true,
        );

        return $pushNotificationSubscriptionsBackendApiAttributesTransfer
            ->setProviderName($pushNotificationProviderName)
            ->setGroup($pushNotificationGroupsBackendApiAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    public function mapPushNotificationSubscriptionsBackendApiAttributesTransferToPushNotificationSubscriptionTransfer(
        PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer,
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): PushNotificationSubscriptionTransfer {
        $pushNotificationSubscriptionsBackendApiAttributesData = array_filter(
            $pushNotificationSubscriptionsBackendApiAttributesTransfer->modifiedToArray(),
            function ($value) {
                return $value !== null;
            },
        );

        $pushNotificationSubscriptionTransfer = $pushNotificationSubscriptionTransfer->fromArray(
            $pushNotificationSubscriptionsBackendApiAttributesData,
            true,
        );

        $pushNotificationProviderTransfer = $this->mapPushNotificationSubscriptionsBackendApiAttributesTransferToPushNotificationProviderTransfer(
            $pushNotificationSubscriptionsBackendApiAttributesTransfer,
            new PushNotificationProviderTransfer(),
        );

        $pushNotificationGroupTransfer = $this->mapPushNotificationSubscriptionsBackendApiAttributesTransferToPushNotificationGroupTransfer(
            $pushNotificationSubscriptionsBackendApiAttributesTransfer,
            new PushNotificationGroupTransfer(),
        );

        if ($pushNotificationSubscriptionsBackendApiAttributesTransfer->getLocaleName()) {
            $localeTransfer = $this->mapPushNotificationSubscriptionsBackendApiAttributesTransferToLocaleTransfer(
                $pushNotificationSubscriptionsBackendApiAttributesTransfer,
                new LocaleTransfer(),
            );
            $pushNotificationSubscriptionTransfer->setLocale($localeTransfer);
        }

        return $pushNotificationSubscriptionTransfer
            ->setProvider($pushNotificationProviderTransfer)
            ->setGroup($pushNotificationGroupTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    public function mapGlueRequestTransferToPushNotificationSubscriptionTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): PushNotificationSubscriptionTransfer {
        $glueRequestUserTransfer = $glueRequestTransfer->getRequestUserOrFail();

        $pushNotificationUserTransfer = new PushNotificationUserTransfer();
        if ($pushNotificationSubscriptionTransfer->getUser()) {
            $pushNotificationUserTransfer = $pushNotificationSubscriptionTransfer->getUserOrFail();
        }

        $pushNotificationUserTransfer
            ->setReference((string)$glueRequestUserTransfer->getSurrogateIdentifierOrFail())
            ->setUuid($glueRequestUserTransfer->getNaturalIdentifierOrFail());

        if ($glueRequestUserTransfer->getScopes()) {
            $pushNotificationUserTransfer->setType(
                current($glueRequestUserTransfer->getScopes()),
            );
        }

        return $pushNotificationSubscriptionTransfer->setUser($pushNotificationUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\PushNotificationProviderTransfer $pushNotificationProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderTransfer
     */
    protected function mapPushNotificationSubscriptionsBackendApiAttributesTransferToPushNotificationProviderTransfer(
        PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer,
        PushNotificationProviderTransfer $pushNotificationProviderTransfer
    ): PushNotificationProviderTransfer {
        $pushNotificationProviderName = $pushNotificationSubscriptionsBackendApiAttributesTransfer->getProviderNameOrFail();

        return $pushNotificationProviderTransfer->setName($pushNotificationProviderName);
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\PushNotificationGroupTransfer $pushNotificationGroupTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationGroupTransfer
     */
    protected function mapPushNotificationSubscriptionsBackendApiAttributesTransferToPushNotificationGroupTransfer(
        PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer,
        PushNotificationGroupTransfer $pushNotificationGroupTransfer
    ): PushNotificationGroupTransfer {
        return $pushNotificationGroupTransfer->fromArray(
            $pushNotificationSubscriptionsBackendApiAttributesTransfer->getGroupOrFail()->toArray(),
            true,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function mapPushNotificationSubscriptionsBackendApiAttributesTransferToLocaleTransfer(
        PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer,
        LocaleTransfer $localeTransfer
    ): LocaleTransfer {
        return $localeTransfer->setLocaleName($pushNotificationSubscriptionsBackendApiAttributesTransfer->getLocaleName());
    }
}
