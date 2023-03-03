<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business;

use Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionResponseTransfer;

interface PushNotificationFacadeInterface
{
    /**
     * Specification:
     * - Fetches a collection of push notification providers from Persistence.
     * - Uses `PushNotificationProviderCriteriaTransfer.pushNotificationConditionsTransfer.names` to filter push notification providers by names.
     * - Uses `PushNotificationProviderCriteriaTransfer.PaginationTransfer.{limit, offset}` to paginate result with limit and offset.
     * - Uses `PushNotificationProviderCriteriaTransfer.PaginationTransfer.{page, maxPerPage}` to paginate result with page and maxPerPage.
     * - Returns `PushNotificationProviderCollectionTransfer` filled with found push notification providers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer $pushNotificationProviderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer
     */
    public function getPushNotificationProviderCollection(
        PushNotificationProviderCriteriaTransfer $pushNotificationProviderCriteriaTransfer
    ): PushNotificationProviderCollectionTransfer;

    /**
     * Specification:
     * - Requires `PushNotificationSubscriptionCollectionRequestTransfer.pushNotificationSubscriptions.provider.name` to be in Persistence.
     * - Requires `PushNotificationSubscriptionCollectionRequestTransfer.pushNotificationGroup.name` to be in the {@link \Spryker\Zed\PushNotification\PushNotificationConfig::GROUP_NAME_ALLOW_LIST} list if this is not empty, otherwise returns an error.
     * - In case `PushNotificationSubscriptionCollectionRequestTransfer.pushNotificationGroup.identifier` is empty user is getting subscribed to all kind of notifications.
     * - Creates `PushNotificationGroup` if it was not found by `PushNotificationSubscription.group.name`.
     * - Executes the stack of {@link \Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSubscriptionValidatorPluginInterface}.
     * - In case the expiration date is not set `PushNotificationSubscriptionCollectionRequestTransfer.pushNotificationSubscriptions.expiredAt’, uses {@link \Spryker\Zed\PushNotification\PushNotificationConfig::getPushNotificationSubscriptionTTL()} to get the push notification subscription lifetime.
     * - Persists push notification subscriptions to Persistence.
     * - Returns persisted push notification subscriptions and validation errors in case of `PushNotificationSubscriptionCollectionRequestTransfer.isTransactional` is false.
     * - Returns persisted push notification subscriptions or validation errors in case of `PushNotificationSubscriptionCollectionRequestTransfer.isTransactional` is true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionRequestTransfer $pushNotificationSubscriptionCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionResponseTransfer
     */
    public function createPushNotificationSubscriptionCollection(
        PushNotificationSubscriptionCollectionRequestTransfer $pushNotificationSubscriptionCollectionRequestTransfer
    ): PushNotificationSubscriptionCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `PushNotificationCollectionRequestTransfer.pushNotifications.provider.name` to be in Persistence.
     * - Creates `PushNotificationGroup` if it was not found by `PushNotification.group.name`.
     * - Executes the stack of {@link \Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationValidatorPluginInterface}.
     * - Persists push notifications to Persistence.
     * - Returns persisted push notifications and validation errors in case of `PushNotificationCollectionRequestTransfer.isTransactional` is false.
     * - Returns persisted push notifications or validation errors in case of `PushNotificationSubscriptionCollectionRequestTransfer.isTransactional` is true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer
     */
    public function createPushNotificationCollection(
        PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
    ): PushNotificationCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `PushNotificationProviderCollectionRequest.pushNotificationProviders.name` transfer property to be set.
     * - Validates if push notification provider with given `PushNotificationProviderCollectionRequest.pushNotificationProviders.name` already exists.
     * - Persists push notification providers to Persistence.
     * - Returns persisted push notification providers and validation errors in case of `PushNotificationProviderCollectionRequestTransfer.isTransactional` is false.
     * - Returns persisted push notification providers or validation errors in case of `PushNotificationProviderCollectionRequestTransfer.isTransactional` is true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionRequestTransfer $pushNotificationProviderCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer
     */
    public function createPushNotificationProviderCollection(
        PushNotificationProviderCollectionRequestTransfer $pushNotificationProviderCollectionRequestTransfer
    ): PushNotificationProviderCollectionResponseTransfer;

    /**
     * Specification:
     * - Fetches collection of not sent PushNotifications from the Persistence.
     * - Sends push notifications according to provider in a batch mode {@link \Spryker\Zed\PushNotification\PushNotificationConfig::getPushNotificationSendBatchSize()}.
     * - Executes the stack of {@link \Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSenderPluginInterface}.
     * - Sets `PushNotification.isNotificationSent` to true in a case of successful send.
     * - Returns sent push notifications and validation errors.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer
     */
    public function sendPushNotifications(): PushNotificationCollectionResponseTransfer;

    /**
     * Specification:
     * - Deletes push notification subscriptions from Persistence by given `PushNotificationSubscriptionCollectionDeleteCriteriaTransfer`.
     * - Deletes in a batch mode {@link \Spryker\Zed\PushNotification\PushNotificationConfig::getPushNotificationDeleteBatchSize()}.
     * - Attention: This method deletes the whole set of push notification subscriptions in the case when empty `PushNotificationSubscriptionCollectionDeleteCriteriaTransfer` given.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionDeleteCriteriaTransfer $pushNotificationSubscriptionCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deletePushNotificationSubscriptionCollection(
        PushNotificationSubscriptionCollectionDeleteCriteriaTransfer $pushNotificationSubscriptionCollectionDeleteCriteriaTransfer
    ): void;
}
