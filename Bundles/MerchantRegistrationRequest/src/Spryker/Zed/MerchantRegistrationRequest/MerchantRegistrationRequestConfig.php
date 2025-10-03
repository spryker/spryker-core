<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantRegistrationRequestConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const STATUS_PENDING = 'pending';

    /**
     * @var string
     */
    public const STATUS_REJECTED = 'rejected';

    /**
     * @var string
     */
    public const STATUS_ACCEPTED = 'accepted';

    /**
     * @var string
     */
    protected const COMMENT_THREAD_MERCHANT_REGISTRATION_REQUEST_OWNER_TYPE = 'merchant_registration_request';

    /**
     * @var array<string, string>
     */
    protected const STATUS_CLASS_LABEL_MAPPING = [
        self::STATUS_PENDING => 'label-warning',
        self::STATUS_ACCEPTED => 'label-success',
        self::STATUS_REJECTED => 'label-danger',
    ];

    /**
     * @uses \Spryker\Zed\MerchantGui\MerchantGuiConfig::PREFIX_MERCHANT_URL
     *
     * @var string
     */
    protected const PREFIX_MERCHANT_URL = 'merchant';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_WAITING_FOR_APPROVAL
     *
     * @var string
     */
    protected const STATUS_WAITING_FOR_APPROVAL = 'waiting-for-approval';

    /**
     * @var string
     */
    protected const MERCHANT_REFERENCE_PREFIX = 'MER';

    /**
     * @var string
     */
    protected const UNIQUE_RANDOM_ID_MERCHANT_REFERENCE_ALPHABET = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @var int
     */
    protected const UNIQUE_RANDOM_ID_MERCHANT_REFERENCE_SIZE = 5;

    /**
     * @var int
     */
    protected const SAVE_MERCHANT_TRANSACTION_MAX_ATTEMPTS = 5;

    /**
     * Specification:
     * - Returns the mapping of merchant registration request statuses to corresponding CSS label classes.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getStatusClassLabelMapping(): array
    {
        return static::STATUS_CLASS_LABEL_MAPPING;
    }

    /**
     * Specification:
     * - Returns the URL prefix for merchant URLs.
     *
     * @api
     */
    public function getMerchantUrlPrefix(): string
    {
        return static::PREFIX_MERCHANT_URL;
    }

    /**
     * Specification:
     * - Returns the prefix for the merchant reference generated for new merchants.
     *
     * @api
     */
    public function getMerchantReferencePrefix(): string
    {
        return static::MERCHANT_REFERENCE_PREFIX;
    }

    /**
     * Specification:
     * - Returns default merchant status after merchant registration request is accepted.
     *
     * @api
     */
    public function getDefaultMerchantStatus(): string
    {
        return static::STATUS_WAITING_FOR_APPROVAL;
    }

    /**
     * Specification:
     * - Returns default merchant registration request status.
     *
     * @api
     */
    public function getDefaultMerchantRegistrationRequestStatus(): string
    {
        return static::STATUS_PENDING;
    }

    /**
     * Specification:
     * - Returns the alphabet for the UniqueRandomId merchant reference. When more symbols are used, there are fewer chances for collision.
     *
     * Example: '0123456789abcdefg'
     *
     * @api
     */
    public function getUniqueRandomIdMerchantReferenceAlphabet(): string
    {
        return static::UNIQUE_RANDOM_ID_MERCHANT_REFERENCE_ALPHABET;
    }

    /**
     * Specification:
     * - Returns the length of the UniqueRandomId merchant reference. Longer size - fewer chances for collision.
     *
     * @api
     */
    public function getUniqueRandomIdMerchantReferenceSize(): int
    {
        return static::UNIQUE_RANDOM_ID_MERCHANT_REFERENCE_SIZE;
    }

    /**
     * Specification:
     * - Returns the maximum number of attempts to save a merchant.
     *
     * @api
     */
    public function getSaveMerchantTransactionMaxAttempts(): int
    {
        return static::SAVE_MERCHANT_TRANSACTION_MAX_ATTEMPTS;
    }

    /**
     * Specification:
     * - Returns the list of acceptable statuses for a merchant registration request.
     *
     * @api
     *
     * @return array<string>
     */
    public function getAcceptableStatuses(): array
    {
        return [
            static::STATUS_PENDING,
        ];
    }

    /**
     * Specification:
     * - Returns the list of rejectable statuses for a merchant registration request.
     *
     * @api
     *
     * @return array<string>
     */
    public function getRejectableStatuses(): array
    {
        return [
            static::STATUS_PENDING,
        ];
    }

    /**
     * Specification:
     * - Returns the owner type for comment threads related to merchant registration requests.
     *
     * @api
     */
    public function getCommentThreadOwnerType(): string
    {
        return static::COMMENT_THREAD_MERCHANT_REGISTRATION_REQUEST_OWNER_TYPE;
    }
}
