<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Shared\SelfServicePortal;

use Spryker\Shared\Kernel\AbstractSharedConfig;
use SprykerFeature\Shared\SelfServicePortal\Exception\SspInquiryCancelStatusNotFound;

class SelfServicePortalConfig extends AbstractSharedConfig
{
    /**
     * Specification
     * - Constant is used to group product-related page data expanders.
     *
     * @api
     *
     * @var string
     */
    public const PLUGIN_PRODUCT_ABSTRACT_CLASS_DATA = 'PLUGIN_PRODUCT_ABSTRACT_CLASS_DATA';

    /**
     * Specification:
     * - Entity type for company.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_TYPE_COMPANY = 'company';

    /**
     * Specification:
     * - Entity type for company user.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_TYPE_COMPANY_USER = 'company_user';

    /**
     * Specification:
     * - Entity type for company business unit.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_TYPE_COMPANY_BUSINESS_UNIT = 'company_business_unit';

    /**
     * Specification:
     * - Entity type for asset.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_TYPE_SSP_ASSET = 'ssp_asset';

    /**
     * @uses \Spryker\Shared\UtilDateTime\UtilDateTimeConstants::DATE_TIME_ZONE
     *
     * @var string
     */
    protected const DATE_TIME_ZONE = 'DATE_TIME_ZONE';

    /**
     * @uses \Spryker\Service\UtilDateTime\Model\DateTimeFormatter::DEFAULT_TIME_ZONE
     *
     * @var string
     */
    protected const DEFAULT_TIME_ZONE = 'Europe/Berlin';

    /**
     * @var string
     */
    protected const SSP_INQUIRY_STATE_MACHINE_NAME = 'SspInquiry';

    /**
     * @var string
     */
    public const GENERAL_SSP_INQUIRY_SOURCE = 'general';

    /**
     * @var string
     */
    public const ORDER_SSP_INQUIRY_SOURCE = 'order';

    /**
     * @var string
     */
    public const SSP_ASSET_SSP_INQUIRY_SOURCE = 'ssp_asset';

    /**
     * @var array<string, array<string>>
     */
    protected const SELECTABLE_SSP_INQUIRY_TYPES_TO_SSP_INQUIRY_SOURCE_MAP = [
        'general' => ['general'],
        'order' => ['order'],
        'ssp_asset' => ['ssp_asset'],
    ];

    /**
     * @var int
     */
    protected const SSP_INQUIRY_FILE_READ_CHUNK_SIZE = 1048576; // 1024 * 1024;

    /**
     * @var int
     */
    protected const SSP_ASSET_IMAGE_READ_CHUNK_SIZE = 1048576; // 1024 * 1024;

    /**
     * @var string
     */
    public const STATUS_PENDING = 'pending';

    /**
     * @var string
     */
    public const STATUS_IN_REVIEW = 'in_review';

    /**
     * @var string
     */
    public const STATUS_APPROVED = 'approved';

    /**
     * @var string
     */
    public const STATUS_DEACTIVATED = 'deactivated';

    /**
     * @var string
     */
    public const ACTION_UNASSIGN = 'unassign';

    /**
     * @var string
     */
    public const ACTION_UPDATE = 'update';

    /**
     * @var string
     */
    public const ACTION_VIEW = 'view';

    /**
     * @var int
     */
    public const SSP_INQUIRY_SUBJECT_MAX_LENGTH = 255;

    /**
     * @var int
     */
    public const SSP_INQUIRY_DESCRIPTION_MAX_LENGTH = 1000;

    /**
     * @var int
     */
    protected const DEFAULT_FILE_DASHBOARD_MAX_PER_PAGE = 3;

    /**
     * Specification:
     * - Returns the default maximum items per page for file dashboard.
     *
     * @api
     *
     * @return int
     */
    public function getDefaultFileDashboardMaxPerPage(): int
    {
        return static::DEFAULT_FILE_DASHBOARD_MAX_PER_PAGE;
    }

    /**
     * Specification:
     * - Returns the service product class name.
     *
     * @api
     *
     * @return string
     */
    public function getServiceProductClassName(): string
    {
        return 'Service';
    }

    /**
     * Specification:
     * - Returns the scheduled product class name.
     *
     * @api
     *
     * @return string
     */
    public function getScheduledProductClassName(): string
    {
        return 'Scheduled';
    }

    /**
     * Specification:
     * - Returns a list of entity types.
     * - These entity types are used to build the file attachment entity type filter in the self-service portal.
     *
     * @api
     *
     * @return list<string>
     */
    public function getEntityTypes(): array
    {
        return [
            static::ENTITY_TYPE_COMPANY_USER,
            static::ENTITY_TYPE_COMPANY,
            static::ENTITY_TYPE_COMPANY_BUSINESS_UNIT,
            static::ENTITY_TYPE_SSP_ASSET,
        ];
    }

    /**
     * Specification:
     * - Returns date time zone.
     * - Used for filtering files by date.
     *
     * @api
     *
     * @return string
     */
    public function getDateTimeZone(): string
    {
        return $this->get(static::DATE_TIME_ZONE, static::DEFAULT_TIME_ZONE);
    }

    /**
     * Specification:
     * - Returns selectable ssp inquiry types available for user selection.
     * - Selectable ssp inquiry types vary based on the ssp inquiry source.
     * - For instance, selectable ssp inquiry types for an order ssp inquiry can include 'order-question', 'order-complaint', etc.
     * - Define selectable ssp inquiry types as an associative array where the key represents the ssp inquiry source and the value is an array of selectable ssp inquiry types.
     *
     * @api
     *
     * @return array<string, array<string>>
     */
    public function getSelectableInquiryTypesToInquirySourceMap(): array
    {
        return static::SELECTABLE_SSP_INQUIRY_TYPES_TO_SSP_INQUIRY_SOURCE_MAP;
    }

    /**
     * Specification:
     * - Returns ssp inquiry event name for ssp inquiry cancellation.
     *
     * @api
     *
     * @return string
     */
    public function getSspInquiryCancelStateMachineEventName(): string
    {
        throw new SspInquiryCancelStatusNotFound();
    }

    /**
     * Specification:
     * - Returns the default total file max size for file uploads during ssp inquiry creation.
     *
     * @api
     *
     * @return string
     */
    public function getSspInquiriesFilesMaxSize(): string
    {
        return $this->get(SelfServicePortalConstants::DEFAULT_TOTAL_FILE_MAX_SIZE);
    }

    /**
     * Specification:
     * - Returns the default file max size per file upload during ssp inquiry creation.
     *
     * @api
     *
     * @return string
     */
    public function getSspInquiryFileMaxSize(): string
    {
        return $this->get(SelfServicePortalConstants::DEFAULT_FILE_MAX_SIZE);
    }

    /**
     * Specification:
     * - Returns the default file max size per file upload during ssp inquiry creation.
     *
     * @api
     *
     * @return string
     */
    public function getSspAssetImageFilesMaxSize(): string
    {
        return $this->get(SelfServicePortalConstants::DEFAULT_FILE_MAX_SIZE);
    }

    /**
     * Specification:
     * - Returns the default file max count during ssp inquiry creation.
     *
     * @api
     *
     * @return int
     */
    public function getSspInquiryFileMaxCount(): int
    {
        return 5;
    }

    /**
     * Specification:
     * - Returns the allowed file extensions for file uploads during ssp inquiry creation.
     *
     * @api
     *
     * @return array<string>
     */
    public function getSspInquiryAllowedFileExtensions(): array
    {
        return ['jpg', 'jpeg', 'png', 'pdf', 'heic'];
    }

    /**
     * Specification:
     * - Returns the allowed file mime types for file uploads during ssp inquiry creation.
     *
     * @api
     *
     * @return array<string>
     */
    public function getSspInquiryAllowedFileMimeTypes(): array
    {
        return [
            'image/png',
            'image/jpeg',
            'image/jpg',
            'image/heic',
            'image/heif',
            'application/pdf',
        ];
    }

    /**
     * Specification:
     * - Returns the ssp inquiry state machine name.
     *
     * @api
     *
     * @return string
     */
    public function getSspInquiryStateMachineName(): string
    {
        return static::SSP_INQUIRY_STATE_MACHINE_NAME;
    }

    /**
     * Specification:
     * - Returns the ssp inquiry type => state machine process map.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getSspInquiryStateMachineProcessInquiryTypeMap(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Returns the ssp inquiry state machine process => initial state map.
     *
     * @example
     * [
     *     'SspInquiryDefaultStateMachine' => 'created',
     * ]
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getInquiryInitialStateMachineMap(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Returns the list of ssp inquiry statuses.
     *
     * @api
     *
     * @return array<string>
     */
    public function getSspInquiryAvailableStatuses(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Returns the maximum length of the subject field for ssp inquiry.
     *
     * @api
     *
     * @return int
     */
    public function getSspInquirySubjectMaxLength(): int
    {
        return static::SSP_INQUIRY_SUBJECT_MAX_LENGTH;
    }

    /**
     * Specification:
     * - Returns the maximum length of the description field for ssp inquiry.
     *
     * @api
     *
     * @return int
     */
    public function getSspInquiryDescriptionMaxLength(): int
    {
        return static::SSP_INQUIRY_DESCRIPTION_MAX_LENGTH;
    }

    /**
     * Specification:
     * - Defines the read chunk size in bytes.
     *
     * @api
     *
     * @return int
     */
    public function getSspAssetImageReadChunkSize(): int
    {
        return static::SSP_ASSET_IMAGE_READ_CHUNK_SIZE;
    }

    /**
     * Specification:
     * - Returns the allowed file extensions for file uploads during ssp asset creation/update.
     *
     * @api
     *
     * @return array<string>
     */
    public function getSspAssetAllowedFileExtensions(): array
    {
        return ['jpg', 'jpeg', 'png'];
    }

    /**
     * Specification:
     * - Returns the allowed file mime types for file uploads during ssp asset create/update.
     *
     * @api
     *
     * @return array<string>
     */
    public function getSspAssetAllowedFileMimeTypes(): array
    {
        return [
            'image/png',
            'image/jpeg',
            'image/jpg',
        ];
    }

    /**
     * Specification:
     * - Returns the default file max size for file uploads for ssp asset.
     *
     * @api
     *
     * @return string
     */
    public function getAssetImageDefaultFileMaxSize(): string
    {
        return '10M';
    }

    /**
     * Specification:
     * - Defines the read chunk size in bytes.
     *
     * @api
     *
     * @return int
     */
    public function getSspInquiryFileReadChunkSize(): int
    {
        return static::SSP_INQUIRY_FILE_READ_CHUNK_SIZE;
    }

    /**
     * Specification:
     * - Returns the asset statuses.
     * - Used for managing the asset status in the ssp asset workflow.
     * - Managed by Backoffice user and visible for company user.
     *
     * @api
     *
     * @return array<string>
     */
    public function getAssetStatuses(): array
    {
        return [
            static::STATUS_PENDING => 'Pending',
            static::STATUS_IN_REVIEW => 'In Review',
            static::STATUS_APPROVED => 'Approved',
            static::STATUS_DEACTIVATED => 'Deactivated',
        ];
    }

    /**
     * @api
     *
     * @return array<string, array<string>>
     */
    public function getSspStatusAllowedActionsMapping(): array
    {
        return [
            static::STATUS_PENDING => [
                static::ACTION_UNASSIGN,
                static::ACTION_UPDATE,
                static::ACTION_VIEW,
            ],
            static::STATUS_IN_REVIEW => [
                static::ACTION_VIEW,
            ],
            static::STATUS_APPROVED => [
                static::ACTION_UNASSIGN,
                static::ACTION_UPDATE,
                static::ACTION_VIEW,
            ],
            static::STATUS_DEACTIVATED => [],
        ];
    }
}
