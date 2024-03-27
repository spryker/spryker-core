<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui;

use Spryker\Shared\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantRelationRequestMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const DEFAULT_MERCHANT_RELATION_REQUEST_TABLE_PAGE_SIZE = 10;

    /**
     * @var int
     */
    protected const MERCHANT_RELATION_REQUEST_TABLE_BUSINESS_UNITS_COLUMN_LIMIT = 1;

    /**
     * @var int
     */
    protected const READ_MERCHANT_RELATION_REQUEST_COLLECTION_BATCH_SIZE = 1000;

    /**
     * @uses \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Controller\MerchantRelationRequestsController::indexAction()
     *
     * @var string
     */
    protected const MERCHANT_RELATION_REQUEST_TABLE_PATH = '/merchant-relation-request-merchant-portal-gui/merchant-relation-requests';

    /**
     * @var string
     */
    protected const MERCHANT_RELATION_REQUEST_TABLE_QUERY = 'table-merchant-relation-request={"page":1,"filter":{"inStatuses":["pending"]}}';

    /**
     * @var string
     */
    protected const MERCHANT_RELATION_TABLE_PATH = '/merchant-relationship-merchant-portal-gui/merchant-relationship';

    /**
     * @var string
     */
    protected const MERCHANT_RELATION_TABLE_QUERY = 'table-merchant-relationship={"page":1,"filter":{"inCompanyIds":["%s"],"createdAt":{"from":"%s","to":"%s"}}}';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_PENDING
     *
     * @var string
     */
    protected const STATUS_PENDING = 'pending';

    /**
     * @var string
     */
    protected const DEFAULT_TIMEZONE = 'UTC';

    /**
     * Specification:
     * - Returns the default page size for the merchant relation request table.
     *
     * @api
     *
     * @return int
     */
    public function getDefaultMerchantRelationRequestTablePageSize(): int
    {
        return static::DEFAULT_MERCHANT_RELATION_REQUEST_TABLE_PAGE_SIZE;
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
     * - Returns the limit of chips for `businessUnits` merchant relation request table column.
     *
     * @api
     *
     * @return int
     */
    public function getMerchantRelationRequestTableBusinessUnitsColumnLimit(): int
    {
        return static::MERCHANT_RELATION_REQUEST_TABLE_BUSINESS_UNITS_COLUMN_LIMIT;
    }

    /**
     * Specification:
     * - Returns merchant portal application base url (scheme, host, port).
     *
     * @api
     *
     * @return string
     */
    public function getMerchantPortalBaseUrl(): string
    {
        return $this->get(MerchantRelationRequestMerchantPortalGuiConstants::BASE_URL_MP);
    }

    /**
     * Specification:
     * - Returns the path for the merchant relation request table.
     *
     * @api
     *
     * @return string
     */
    public function getMerchantRelationRequestTablePath(): string
    {
        return static::MERCHANT_RELATION_REQUEST_TABLE_PATH;
    }

    /**
     * Specification:
     * - Returns the query string for the merchant relation request table.
     *
     * @api
     *
     * @return string
     */
    public function getMerchantRelationRequestTableQuery(): string
    {
        return static::MERCHANT_RELATION_REQUEST_TABLE_QUERY;
    }

    /**
     * Specification:
     * - Returns the path for the merchant relation table.
     *
     * @api
     *
     * @return string
     */
    public function getMerchantRelationTablePath(): string
    {
        return static::MERCHANT_RELATION_TABLE_PATH;
    }

    /**
     * Specification:
     * - Returns the query string for the merchant relation table.
     *
     * @api
     *
     * @return string
     */
    public function getMerchantRelationTableQuery(): string
    {
        return static::MERCHANT_RELATION_TABLE_QUERY;
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

    /**
     * Specification:
     * - Returns the default timezone.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultTimezone(): string
    {
        return static::DEFAULT_TIMEZONE;
    }
}
