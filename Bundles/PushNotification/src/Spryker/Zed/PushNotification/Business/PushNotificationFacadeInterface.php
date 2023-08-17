<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business;

use Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionDeleteCriteriaTransfer;
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
     * - Retrieves push notification provider entities filtered by criteria from Persistence.
     * - Uses `PushNotificationProviderCriteriaTransfer.pushNotificationConditionsTransfer.names` to filter by push notification provider names.
     * - Uses `PushNotificationProviderCriteriaTransfer.pushNotificationConditionsTransfer.uuids` to filter by push notification provider uuids.
     * - Inverses uuids filtering in case `PushNotificationProviderCriteriaTransfer.pushNotificationConditionsTransfer.isUuidsConditionInversed` is set to `true`.
     * - Uses `PushNotificationProviderCriteriaTransfer.sort.field` to set the 'order by' field.
     * - Uses `PushNotificationProviderCriteriaTransfer.sort.isAscending` to set ascending/descending order.
     * - Uses `PushNotificationProviderCriteriaTransfer.pagination.{limit, offset}` to paginate results with limit and offset.
     * - Uses `PushNotificationProviderCriteriaTransfer.pagination.{page, maxPerPage}` to paginate results with page and maxPerPage.
     * - Returns `PushNotificationProviderCollectionTransfer` filled with found push notification provider entities.
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
     * - Requires `PushNotificationProviderCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `PushNotificationProviderCollectionRequestTransfer.pushNotificationProviders` to be set.
     * - Requires `PushNotificationProviderTransfer.name` to be set.
     * - Validates push notification provider name length.
     * - Validates push notification provider name uniqueness in scope of request collection.
     * - Validates push notification provider name uniqueness among already persisted push notification providers.
     * - Uses `PushNotificationProviderCollectionRequestTransfer.isTransactional` for transactional operation.
     * - Stores push notification providers at Persistence.
     * - Returns `PushNotificationProviderCollectionResponseTransfer` with push notification providers and errors if any occurred.
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
     * - Requires `PushNotificationProviderCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `PushNotificationProviderCollectionRequestTransfer.pushNotificationProviders` to be set.
     * - Requires `PushNotificationProviderTransfer.uuid` to be set.
     * - Requires `PushNotificationProviderTransfer.name` to be set.
     * - Validates push notification provider existence using `PushNotificationProviderTransfer.uuid`.
     * - Validates push notification provider name length.
     * - Validates push notification provider name uniqueness in scope of request collection.
     * - Validates push notification provider name uniqueness among already persisted push notification providers.
     * - Uses `PushNotificationProviderCollectionRequestTransfer.isTransactional` for transactional operation.
     * - Updates push notification providers at Persistence.
     * - Returns `PushNotificationProviderCollectionResponseTransfer` filled with persisted push notification providers and errors if any occurred.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionRequestTransfer $pushNotificationProviderCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer
     */
    public function updatePushNotificationProviderCollection(
        PushNotificationProviderCollectionRequestTransfer $pushNotificationProviderCollectionRequestTransfer
    ): PushNotificationProviderCollectionResponseTransfer;

    /**
     * Specification:
     * - Deletes collection of push notification providers from storage by delete criteria.
     * - Validates push notification provider usage among persisted push notifications.
     * - Validates push notification provider usage among persisted push notification subscriptions.
     * - Uses `PushNotificationProviderCollectionDeleteCriteriaTransfer.uuids` to filter by push notification provider uuids.
     * - Uses `PushNotificationProviderCollectionDeleteCriteriaTransfer.isTransactional` for transactional operation.
     * - Returns `PushNotificationProviderCollectionResponseTransfer` filled with deleted push notification providers and errors if any occurred.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionDeleteCriteriaTransfer $pushNotificationProviderCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer
     */
    public function deletePushNotificationProviderCollection(
        PushNotificationProviderCollectionDeleteCriteriaTransfer $pushNotificationProviderCollectionDeleteCriteriaTransfer
    ): PushNotificationProviderCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `PushNotificationSubscriptionCollectionRequestTransfer.pushNotificationSubscriptions.provider.name` to be in Persistence.
     * - Requires `PushNotificationSubscriptionCollectionRequestTransfer.pushNotificationGroup.name` to be in the {@link \Spryker\Zed\PushNotification\PushNotificationConfig::GROUP_NAME_ALLOW_LIST} list if this is not empty, otherwise returns an error.
     * - Requires `PushNotificationSubscriptionCollectionRequestTransfer.pushNotificationSubscriptions.locale.localeName` to be set if `PushNotificationSubscriptionCollectionRequestTransfer.pushNotificationSubscriptions.locale` is set.
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
     * - Expects `PushNotificationCollectionRequestTransfer.pushNotifications` to be provided.
     * - Sends push notifications according to provider in a batch mode {@link \Spryker\Zed\PushNotification\PushNotificationConfig::getPushNotificationSendBatchSize()}.
     * - Executes the stack of {@link \Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationPreSendPluginInterface}.
     * - Executes the stack of {@link \Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSenderPluginInterface}.
     * - Sets `PushNotification.isNotificationSent` to true in a case of successful send.
     * - Returns sent push notifications and validation errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer
     */
    public function sendPushNotifications(
        PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
    ): PushNotificationCollectionResponseTransfer;

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

    /**
     * Specification:
     * - Retrieves push notification entities filtered by criteria from Persistence.
     * - Uses `PushNotificationCriteriaTransfer.pushNotificationConditions.pushNotificationIds` to filter by push notification IDs.
     * - Uses `PushNotificationCriteriaTransfer.pushNotificationConditions.pushNotificationProviderIds` to filter by push notification provider IDs.
     * - Uses `PushNotificationCriteriaTransfer.pushNotificationConditions.uuids` to filter by push notification UUIDs.
     * - Uses `PushNotificationCriteriaTransfer.pushNotificationConditions.notificationSent` to filter by successful sent push notifications.
     * - Uses `PushNotificationCriteriaTransfer.sort.field` to set the 'order by' field.
     * - Uses `PushNotificationCriteriaTransfer.sort.isAscending` to set ascending/descending order.
     * - Uses `PushNotificationCriteriaTransfer.pagination.{limit, offset}` to paginate results with limit and offset.
     * - Uses `PushNotificationCriteriaTransfer.pagination.{page, maxPerPage}` to paginate results with page and maxPerPage.
     * - Returns `PushNotificationCollectionTransfer` filled with found push notifications.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationCriteriaTransfer $pushNotificationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionTransfer
     */
    public function getPushNotificationCollection(
        PushNotificationCriteriaTransfer $pushNotificationCriteriaTransfer
    ): PushNotificationCollectionTransfer;
}
