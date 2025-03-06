<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement;

use Spryker\Yves\Kernel\AbstractBundleConfig;

/**
 * @method \SprykerFeature\Shared\SspInquiryManagement\SspInquiryManagementConfig getSharedConfig()
 */
class SspInquiryManagementConfig extends AbstractBundleConfig
{
    /**
     * @var array<string, string>
     */
    protected const BACK_URL_TYPE_TO_PATH_MAP = [
        'order' => 'customer/order/details',
    ];

    /**
     * @var array<string, string>
     */
    protected const BACK_URL_TYPE_TO_IDENTIFIER_MAP = [
        'order' => 'id',
    ];

    /**
     * @var string
     */
    protected const PARAM_PAGE = 'page';

    /**
     * @var string
     */
    protected const PARAM_PER_PAGE = 'perPage';

    /**
     * Specification:
     * - Returns selectable ssp inquiry types that can be selected by the user.
     *
     * @api
     *
     * @return array<string, array<string>>
     */
    public function getSelectableSspInquiryTypes(): array
    {
        return $this->getSharedConfig()->getSelectableSspInquiryTypesToSspInquirySourceMap();
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
        return $this->getSharedConfig()->getAllSelectableSspInquiryTypes();
    }

    /**
     * Specification:
     * - Returns the default total file max size for file uploads during ssp inquiry creation.
     * - File size can be given with units: Kb Mb or Gb.
     *
     * @api
     *
     * @return string
     */
    public function getFilesMaxSize(): string
    {
        return $this->getSharedConfig()->getDefaultTotalFileMaxSize();
    }

    /**
     * Specification:
     * - Returns the default file max size per file upload during ssp inquiry creation.
     * - File size can be given with units: Kb Mb or Gb.
     *
     * @api
     *
     * @return string
     */
    public function getFileMaxSize(): string
    {
        return $this->getSharedConfig()->getDefaultFileMaxSize();
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
        return $this->getSharedConfig()->getFileMaxCount();
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
        return $this->getSharedConfig()->getAllowedFileExtensions();
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
        return $this->getSharedConfig()->getAllowedFileMimeTypes();
    }

    /**
     * Specification:
     * - Returns exact ssp inquiry event name for ssp inquiry cancellation.
     * - Supported only in State Machines where Cancel event is allowed.
     *
     * @api
     *
     * @return string
     */
    public function getSspInquiryCancelStateMachineEventName(): string
    {
        return $this->getSharedConfig()->getSspInquiryCancelStateMachineEventName();
    }

    /**
     * Specification:
     * - Returns parameter name for ssp inquiries per page.
     *
     * @api
     *
     * @return string
     */
    public function getParamPerPage(): string
    {
        return static::PARAM_PER_PAGE;
    }

    /**
     * Specification:
     * - Returns parameter name for ssp inquiries page number.
     *
     * @api
     *
     * @return string
     */
    public function getParamPage(): string
    {
        return static::PARAM_PAGE;
    }

    /**
     * Specification:
     * - Returns page size for ssp inquiries list page.
     *
     * @api
     *
     * @return int
     */
    public function getSspInquiryCountPerPageList(): int
    {
        return 10;
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
        return $this->getSharedConfig()->getAvailableStatuses();
    }

    /**
     * Specification:
     * - Returns url path for back navigation at ssp inquiry creation page.
     *
     * @api
     *
     * @param string $backUrlType
     *
     * @return string|null
     */
    public function getBackUrlPath(string $backUrlType): ?string
    {
        return static::BACK_URL_TYPE_TO_PATH_MAP[$backUrlType] ?? null;
    }

    /**
     * Specification:
     * - Returns url identifier for back navigation at ssp inquiry creation page.
     * - For example 'id' for navigation back to order detail page.
     * - If you need other identifier, you can add it to the map.
     * - For example 'sku' for navigation back to product detail page.
     *
     * @api
     *
     * @param string $backUrlType
     *
     * @return string
     */
    public function getBackUrlIdentifier(string $backUrlType): string
    {
        return static::BACK_URL_TYPE_TO_IDENTIFIER_MAP[$backUrlType] ?? 'id';
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
        return $this->getSharedConfig()->getDownloadChunkSize();
    }
}
