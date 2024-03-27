<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig getSharedConfig()
 */
class MerchantRelationRequestConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';

    /**
     * @var int
     */
    protected const READ_MERCHANT_RELATION_REQUEST_COLLECTION_BATCH_SIZE = 1000;

    /**
     * @uses {@link \SprykerShop\Yves\MerchantRelationRequestPage\Controller\MerchantRelationRequestViewController::detailsAction()}
     *
     * @var string
     */
    protected const MERCHANT_RELATION_REQUEST_PATH = '/company/merchant-relation-request/details';

    /**
     * Specification:
     * - Returns a list of request statuses that can be canceled.
     *
     * @api
     *
     * @return list<string>
     */
    public function getCancelableRequestStatuses(): array
    {
        return $this->getSharedConfig()->getCancelableRequestStatuses();
    }

    /**
     * Specification:
     * - Returns a list of request statuses that can be rejected.
     *
     * @api
     *
     * @return list<string>
     */
    public function getRejectableRequestStatuses(): array
    {
        return $this->getSharedConfig()->getRejectableRequestStatuses();
    }

    /**
     * Specification:
     * - Returns a list of request statuses that can be approved.
     *
     * @api
     *
     * @return list<string>
     */
    public function getApprovableRequestStatuses(): array
    {
        return $this->getSharedConfig()->getApprovableRequestStatuses();
    }

    /**
     * Specification:
     * - Returns a list of request statuses that can be updated to pending status.
     *
     * @api
     *
     * @return list<string>
     */
    public function getPendingUpdateRequestStatuses(): array
    {
        return $this->getSharedConfig()->getPendingUpdateRequestStatuses();
    }

    /**
     * Specification:
     * - Returns a list of fields that are allowed to be modified during the cancelation process of a merchant relation request.
     *
     * @api
     *
     * @return list<string>
     */
    public function getModifiableFieldsAllowedForCancelation(): array
    {
        return [
           MerchantRelationRequestTransfer::STATUS,
        ];
    }

    /**
     * Specification:
     * - Returns a list of fields that are allowed to be modified during the rejection process of a merchant relation request.
     *
     * @api
     *
     * @return list<string>
     */
    public function getModifiableFieldsAllowedForRejection(): array
    {
        return [
           MerchantRelationRequestTransfer::STATUS,
           MerchantRelationRequestTransfer::DECISION_NOTE,
        ];
    }

    /**
     * Specification:
     * - Returns a list of fields that are allowed to be modified during the approval process of a merchant relation request.
     *
     * @api
     *
     * @return list<string>
     */
    public function getModifiableFieldsAllowedForApproval(): array
    {
        return [
           MerchantRelationRequestTransfer::STATUS,
           MerchantRelationRequestTransfer::DECISION_NOTE,
           MerchantRelationRequestTransfer::IS_SPLIT_ENABLED,
           MerchantRelationRequestTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS,
        ];
    }

    /**
     * Specification:
     * - Returns a list of fields that are allowed to be modified during the merchant relation request update in pending status.
     *
     * @api
     *
     * @return list<string>
     */
    public function getModifiableFieldsAllowedForPendingUpdate(): array
    {
        return [
            MerchantRelationRequestTransfer::DECISION_NOTE,
            MerchantRelationRequestTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS,
        ];
    }

    /**
     * Specification:
     * - Returns the merchant status that indicates the merchant is approved.
     *
     * @api
     *
     * @return string
     */
    public function getMerchantStatusApproved(): string
    {
        return static::MERCHANT_STATUS_APPROVED;
    }

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
     * - Returns base URL for Yves including scheme and port (e.g. http://www.de.demoshop.local:8080).
     *
     * @api
     *
     * @return string
     */
    public function getYvesBaseUrl(): string
    {
        return $this->get(MerchantRelationRequestConstants::BASE_URL_YVES);
    }

    /**
     * Specification:
     * - Returns Storefront merchant relation request page path.
     *
     * @api
     *
     * @return string
     */
    public function getMerchantRelationRequestPath(): string
    {
        return static::MERCHANT_RELATION_REQUEST_PATH;
    }

    /**
     * Specification:
     * - Returns list of merchant relation request statuses which trigger status update notification email sending.
     *
     * @api
     *
     * @return list<string>
     */
    public function getApplicableForRequestStatusChangeMailNotificationStatuses(): array
    {
        return $this->getSharedConfig()->getApplicableForRequestStatusChangeMailNotificationStatuses();
    }
}
