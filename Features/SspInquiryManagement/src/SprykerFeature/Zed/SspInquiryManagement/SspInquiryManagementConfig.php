<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement;

use Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\SspInquiryManagement\SspInquiryManagementConstants;

/**
 * @method \SprykerFeature\Shared\SspInquiryManagement\SspInquiryManagementConfig getSharedConfig()
 */
class SspInquiryManagementConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Import type for ssp inquiries.
     *
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_SSP_INQUIRY = 'ssp-inquiry';

    /**
     * @var string
     */
    protected const SSP_INQUIRY_REFERENCE_PREFIX = 'INQR';

    /**
     * @var string
     */
    protected const NAME_SSP_INQUIRY_REFERENCE = 'SspInquiryReference';

    /**
     * @var string
     */
    protected const MODULE_NAME = 'ssp-inquiry-management';

    /**
     * Specification:
     * - Returns the settings for the ssp inquiry sequence number.
     *
     * @api
     *
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    public function getSspInquirySequenceNumberSettings(string $storeName): SequenceNumberSettingsTransfer
    {
        return (new SequenceNumberSettingsTransfer())
            ->setName(static::NAME_SSP_INQUIRY_REFERENCE)
            ->setPrefix($this->createPrefix($storeName));
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
     * @param string $storeName
     *
     * @return string
     */
    protected function createPrefix(string $storeName): string
    {
        $sequenceNumberPrefixParts = [];
        $sequenceNumberPrefixParts[] = $storeName;
        $sequenceNumberPrefixParts[] = static::SSP_INQUIRY_REFERENCE_PREFIX;

        return sprintf('%s--', implode('-', $sequenceNumberPrefixParts));
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
        return $this->getSharedConfig()->getSspInquiryStateMachineName();
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
        return $this->getSharedConfig()->getSspInquiryStateMachineProcessSspInquiryTypeMap();
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
        return $this->getSharedConfig()->getSspInquiryInitialStateMap();
    }

    /**
     * Specification:
     * - Returns exact ssp inquiry event name for ssp inquiry cancellation.
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
     * - Returns base URL for Yves including scheme and port (e.g. http://www.de.demoshop.local:8080).
     *
     * @api
     *
     * @return string
     */
    public function getYvesBaseUrl(): string
    {
        return $this->get(SspInquiryManagementConstants::BASE_URL_YVES);
    }

    /**
     * Specification:
     * - Returns the ssp inquiry status to СSS class name mapping.
     * - Used for status indicator styling.
     *
     * @api
     *
     * @return array<string>
     */
    public function getSspInquiryStatusClassMap(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Returns the ssp inquiry status that is considered as "Pending".
     *
     * @api
     *
     * @return string|null
     */
    public function getPendingStatus(): ?string
    {
        return '';
    }

    /**
     *  Specification:
     *  - Import configuration for ssp inquiry.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer
     */
    public function getSspInquiryDataImporterConfiguration(): DataImporterDataSourceConfigurationTransfer
    {
        return (new DataImporterDataSourceConfigurationTransfer())
            ->setImportType(static::IMPORT_TYPE_SSP_INQUIRY)
            ->setFileName('ssp_inquiry.csv')
            ->setModuleName(static::MODULE_NAME)
            ->setDirectory('/data/data/import/common/common/');
    }

    /**
     * Specification:
     * - Defines the Storage ssp inquiry files storage.
     *
     * @api
     *
     * @return string
     */
    public function getStorageName(): string
    {
        return $this->getSharedConfig()->getStorageName();
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
