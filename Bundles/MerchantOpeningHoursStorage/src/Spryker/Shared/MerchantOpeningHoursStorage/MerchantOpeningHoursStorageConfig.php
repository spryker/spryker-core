<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantOpeningHoursStorage;

class MerchantOpeningHoursStorageConfig
{
    /**
     * Specification:
     * - Queue name as used for processing merchant opening hours messages.
     *
     * @api
     */
    public const MERCHANT_OPENING_HOURS_SYNC_STORAGE_QUEUE = 'sync.storage.merchant_opening_hours';

    /**
     * Specification:
     * - Queue name as used for processing merchant opening hours messages.
     *
     * @api
     */
    public const MERCHANT_OPENING_HOURS_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.merchant_opening_hours.error';

    /**
     * Specification:
     * - Key generation resource name of merchant opening hours.
     *
     * @api
     */
    public const MERCHANT_OPENING_HOURS_RESOURCE_NAME = 'merchant_opening_hours';

    /**
     * Specification:
     * - This event is used for merchant_opening_hours_weekday_schedule and merchant_opening_hours_date_schedule publishing.
     *
     * @api
     */
    public const MERCHANT_OPENING_HOURS_PUBLISH = 'MerchantOpeningHours.merchant_opening_hours_schedule.publish';

    /**
     * Specification:
     * - This event is used for spy_merchant_opening_hours_weekday_schedule entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_OPENING_HOURS_WEEKDAY_SCHEDULE_CREATE = 'Entity.spy_merchant_opening_hours_weekday_schedule.create';

    /**
     * Specification:
     * - This event is used for spy_merchant_opening_hours_date_schedule entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_OPENING_HOURS_DATE_SCHEDULE_CREATE = 'Entity.spy_merchant_opening_hours_date_schedule.create';
}
