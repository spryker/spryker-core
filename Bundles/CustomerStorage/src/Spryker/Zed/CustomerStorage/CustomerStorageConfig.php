<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage;

use Spryker\Shared\CustomerStorage\CustomerStorageConfig as SprykerSharedCustomerStorageConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CustomerStorageConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const DATE_TIME_PERIOD_INVALIDATED_STORAGE_RECORD_LIFE_TIME = 'P01D';

    /**
     * @var int
     */
    protected const BATCH_SIZE_LIMIT = 200;

    /**
     * Specification:
     * - Uses in `spy_customer_invalidated_storage.data` field.
     * - Holds anonymization datetime.
     *
     * @api
     *
     * @var string
     */
    public const COL_ANONYMIZED_AT = 'anonymized_at';

    /**
     * Specification:
     * - Uses in `spy_customer_invalidated_storage.data` field.
     * - Holds password change datetime.
     *
     * @api
     *
     * @var string
     */
    public const COL_PASSWORD_UPDATED_AT = 'password_updated_at';

    /**
     * Specification:
     *  - Returns synchronization queue pool name for broadcasting messages.
     *
     * @api
     *
     * @return string|null
     */
    public function getCustomerInvalidatedSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * Specification:
     * - Returns queue name.
     *
     * @api
     *
     * @return string|null
     */
    public function getEventQueueName(): ?string
    {
        return SprykerSharedCustomerStorageConfig::PUBLISH_CUSTOMER_INVALIDATED;
    }

    /**
     * Specification:
     * - Returns `CustomerInvalidatedStorage` record lifetime.
     * - Should be equal or more than {@see \Spryker\Shared\Session\SessionConstants::YVES_SESSION_TIME_TO_LIVE}.
     * - Should be equal or more than {@see \Spryker\Shared\Oauth\OauthConfig::getAccessTokenTTL()}.
     *
     * @api
     *
     * @return string
     */
    public function getCustomerInvalidatedStorageRecordLifeTime(): string
    {
        return static::DATE_TIME_PERIOD_INVALIDATED_STORAGE_RECORD_LIFE_TIME;
    }

    /**
     * Specification:
     * - Returns batch size limit to delete `CustomerInvalidatedStorage` records.
     *
     * @api
     *
     * @return int
     */
    public function getBatchSizeLimit(): int
    {
        return static::BATCH_SIZE_LIMIT;
    }
}
