<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantRelationRequestGuiConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const READ_MERCHANT_RELATION_REQUEST_COLLECTION_BATCH_SIZE = 1000;

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_PENDING
     *
     * @var string
     */
    protected const STATUS_PENDING = 'pending';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_REJECTED
     *
     * @var string
     */
    protected const STATUS_REJECTED = 'rejected';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_CANCELED
     *
     * @var string
     */
    protected const STATUS_CANCELED = 'canceled';

    /**
     * @var array<string, string>
     */
    protected const STATUS_CLASS_LABEL_MAPPING = [
        self::STATUS_PENDING => 'label-warning',
        self::STATUS_APPROVED => 'label-success',
        self::STATUS_REJECTED => 'label-danger',
        self::STATUS_CANCELED => 'label-info',
    ];

    /**
     * Specification:
     * - Returns the batch size for merchant relation request collection reading.
     *
     * @api
     *
     * @return int
     */
    public function getReadMerchantRelationRequestCollectionBatchSize(): int
    {
        return static::READ_MERCHANT_RELATION_REQUEST_COLLECTION_BATCH_SIZE;
    }

    /**
     * Specification:
     * - Returns the mapping of merchant relation request statuses to corresponding CSS label classes.
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
     * - Returns the list of merchant relation request statuses in which merchant relation request can be edited.
     *
     * @api
     *
     * @return list<string>
     */
    public function getEditableMerchantRelationRequestStatuses(): array
    {
        return [
            static::STATUS_PENDING,
        ];
    }
}
