<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Shared\SspInquiryManagement;

use Spryker\Shared\Kernel\AbstractSharedConfig;
use SprykerFeature\Shared\SspInquiryManagement\Exception\SspInquiryCancelStatusNotFound;

class SspInquiryManagementConfig extends AbstractSharedConfig
{
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
    protected const DOWNLOAD_CHUNK_SIZE = 1048576; // 1024 * 1024;

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
    public function getSelectableSspInquiryTypesToSspInquirySourceMap(): array
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
        return throw new SspInquiryCancelStatusNotFound();
    }

    /**
     * Specification:
     * - Returns all selectable ssp inquiry types that can be selected by the user.
     *
     * @api
     *
     * @return array<string>
     */
    public function getAllSelectableSspInquiryTypes(): array
    {
        return array_merge(...array_values(static::SELECTABLE_SSP_INQUIRY_TYPES_TO_SSP_INQUIRY_SOURCE_MAP));
    }

    /**
     * Specification:
     * - Returns the default total file max size for file uploads during ssp inquiry creation.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultTotalFileMaxSize(): string
    {
        return $this->get(SspInquiryManagementConstants::DEFAULT_TOTAL_FILE_MAX_SIZE);
    }

    /**
     * Specification:
     * - Returns the default file max size per file upload during ssp inquiry creation.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultFileMaxSize(): string
    {
        return $this->get(SspInquiryManagementConstants::DEFAULT_FILE_MAX_SIZE);
    }

    /**
     * Specification:
     * - Returns the default file max count during ssp inquiry creation.
     *
     * @api
     *
     * @return int
     */
    public function getFileMaxCount(): int
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
    public function getAllowedFileExtensions(): array
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
    public function getAllowedFileMimeTypes(): array
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
    public function getSspInquiryStateMachineProcessSspInquiryTypeMap(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Returns the ssp inquiry state machine process => initial state map.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getSspInquiryInitialStateMap(): array
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
    public function getAvailableStatuses(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Defines the Storage for ssp inquiry files.
     * - A `FileSystemConstants::FILESYSTEM_SERVICE` with the same storage name must be defined.
     *
     * @api
     *
     * @return string
     */
    public function getStorageName(): string
    {
        return '';
    }

    /**
     * Specification:
     * - Defines the download chunk size in bytes.
     *
     * @api
     *
     * @return int
     */
    public function getDownloadChunkSize(): int
    {
        return static::DOWNLOAD_CHUNK_SIZE;
    }
}
