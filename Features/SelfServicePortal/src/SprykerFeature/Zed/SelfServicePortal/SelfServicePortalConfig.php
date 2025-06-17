<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConstants;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Exception\DefaultMerchantNotConfiguredException;

/**
 * @method \SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig getSharedConfig()
 */
class SelfServicePortalConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Import type for product shipment type.
     *
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_PRODUCT_SHIPMENT_TYPE = 'product-shipment-type';

    /**
     * Specification:
     * - Import type for product abstract type.
     *
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_PRODUCT_ABSTRACT_TYPE = 'product-abstract-type';

    /**
     * Specification:
     * - Import type for product abstract to product abstract type relation.
     *
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_PRODUCT_ABSTRACT_TO_PRODUCT_ABSTRACT_TYPE = 'product-abstract-product-abstract-type';

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
     * @uses \Spryker\Shared\ShipmentType\ShipmentTypeConfig::SHIPMENT_TYPE_DELIVERY
     *
     * @var string
     */
    protected const DEFAULT_SHIPMENT_TYPE = 'delivery';

    /**
     * Specification:
     * - Default sort field for file dashboard.
     *
     * @api
     *
     * @var string
     */
    public const DEFAULT_FILE_DASHBOARD_SORT_FIELD = 'createdAt';

    /**
     * Specification:
     * - Default sort direction for file dashboard.
     *
     * @api
     *
     * @var bool
     */
    public const DEFAULT_FILE_DASHBOARD_SORT_IS_ASCENDING = false;

    /**
     * Specification:
     * - Default page number for file dashboard.
     *
     * @api
     *
     * @var int
     */
    public const DEFAULT_FILE_DASHBOARD_PAGE_NUMBER = 1;

    /**
     * Specification:
     * - Default maximum items per page for file dashboard.
     *
     * @api
     *
     * @var int
     */
    protected const DEFAULT_FILE_DASHBOARD_MAX_PER_PAGE = 3;

    /**
     * Specification:
     * - Default page number for inquiry asset expansion pagination.
     *
     * @api
     *
     * @var int
     */
    protected const SSP_INQUIRY_ASSET_EXPANDER_PAGE_NUMBER = 1;

    /**
     * Specification:
     * - Default max per page for inquiry asset expansion pagination.
     *
     * @api
     *
     * @var int
     */
    protected const SSP_INQUIRY_ASSET_EXPANDER_MAX_PER_PAGE = 3;

    /**
     * Specification:
     * - Default page number for inquiry dashboard.
     *
     * @api
     *
     * @var int
     */
    protected const DEFAULT_INQUIRY_DASHBOARD_PAGE_NUMBER = 1;

    /**
     * Specification:
     * - Default maximum items per page for inquiry dashboard.
     *
     * @api
     *
     * @var int
     */
    protected const DEFAULT_INQUIRY_DASHBOARD_MAX_PER_PAGE = 3;

    /**
     * Specification:
     * - Default sort field for inquiry dashboard.
     *
     * @api
     *
     * @var string
     */
    protected const DEFAULT_INQUIRY_DASHBOARD_SORT_FIELD = 'spy_ssp_inquiry.created_at';

    /**
     * Specification:
     * - Default sort direction for inquiry dashboard.
     *
     * @api
     *
     * @var bool
     */
    protected const DEFAULT_INQUIRY_DASHBOARD_SORT_IS_ASCENDING = false;

    /**
     * @var string
     */
    protected const DEFAULT_MAX_FILE_SIZE = '100M';

    /**
     * @var array<string>
     */
    protected const DEFAULT_ALLOWED_MIME_TYPES = [
        'application/pdf',
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/heic',
        'image/heif',
    ];

    /**
     * @var array<string>
     */
    protected const DEFAULT_ALLOWED_FILE_EXTENSIONS = [
        '.pdf',
        '.jpeg',
        '.jpg',
        '.png',
        '.heic',
        '.heif',
    ];

    /**
     * @var string
     */
    protected const FILE_REFERENCE_PREFIX = 'FILE-';

    /**
     * @var string
     */
    protected const FILE_REFERENCE_NAME = 'FileReference';

    /**
     * @var int
     */
    protected const DEFAULT_FILE_ATTACHMENT_FORM_AUTOCOMPLETE_LIMIT = 10;

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
    protected const MODULE_NAME = 'self-service-portal';

    /**
     * @var string
     */
    protected const NAME_SSP_ASSET_REFERENCE = 'SspAssetReference';

    /**
     * @var string
     */
    protected const SSP_ASSET_REFERENCE_PREFIX = 'AST';

    /**
     * @var string
     */
    protected const INITIAL_SSP_ASSET_STATUS = 'pending';

    /**
     * Specification:
     * - Import configuration for product shipment type.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer
     */
    public function getProductShipmentTypeDataImporterConfiguration(): DataImporterDataSourceConfigurationTransfer
    {
        return (new DataImporterDataSourceConfigurationTransfer())
            ->setImportType(static::IMPORT_TYPE_PRODUCT_SHIPMENT_TYPE)
            ->setFileName('product_shipment_type.csv')
            ->setModuleName('SelfServicePortal')
            ->setDirectory('/data/data/import/common/common/');
    }

    /**
     * Specification:
     * - Import configuration for product abstract type.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer
     */
    public function getProductAbstractTypeDataImporterConfiguration(): DataImporterDataSourceConfigurationTransfer
    {
        return (new DataImporterDataSourceConfigurationTransfer())
            ->setImportType(static::IMPORT_TYPE_PRODUCT_ABSTRACT_TYPE)
            ->setFileName('product_abstract_type.csv')
            ->setModuleName('SelfServicePortal')
            ->setDirectory('/data/data/import/common/common/');
    }

    /**
     * Specification:
     * - Import configuration for product abstract to product abstract type relation.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer
     */
    public function getProductAbstractToProductAbstractTypeDataImporterConfiguration(): DataImporterDataSourceConfigurationTransfer
    {
        return (new DataImporterDataSourceConfigurationTransfer())
            ->setImportType(static::IMPORT_TYPE_PRODUCT_ABSTRACT_TO_PRODUCT_ABSTRACT_TYPE)
            ->setFileName('product_abstract_product_abstract_type.csv')
            ->setModuleName('SelfServicePortal')
            ->setDirectory('/data/data/import/common/common/');
    }

    /**
     * @api
     *
     * @param string $fileName
     * @param string $importType
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function buildImporterConfiguration(
        string $fileName,
        string $importType
    ): DataImporterConfigurationTransfer {
        $dataImporterReaderConfiguration = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfiguration->setFileName($fileName);

        $dataImporterConfiguration = new DataImporterConfigurationTransfer();
        $dataImporterConfiguration
            ->setImportType($importType)
            ->setReaderConfiguration($dataImporterReaderConfiguration);

        return $dataImporterConfiguration;
    }

    /**
     * Specification:
     * - Returns the default shipment type key.
     * - The default shipment type key is used for new products.
     * - The default shipment type key is used for the cart items if no shipment type is set.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultShipmentType(): string
    {
        return static::DEFAULT_SHIPMENT_TYPE;
    }

    /**
     * Specification:
     * - Returns a merchant reference that is used for product offer creation.
     *
     * @api
     *
     * @throws \SprykerFeature\Zed\SelfServicePortal\Business\Service\Exception\DefaultMerchantNotConfiguredException
     *
     * @return string
     */
    public function getDefaultMerchantReference(): string
    {
        throw new DefaultMerchantNotConfiguredException();
    }

    /**
     * Specification:
     * - Returns the product service type name.
     *
     * @api
     *
     * @return string
     */
    public function getServiceProductTypeName(): string
    {
        return $this->getSharedConfig()->getServiceProductTypeName();
    }

    /**
     * @example The format of returned array is:
     * [
     *    'PAYMENT_METHOD_1' => StateMachineProcess_1',
     *    'PAYMENT_METHOD_2' => StateMachineProcess_2',
     * ]
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getPaymentMethodStateMachineProcessMapping(): array
    {
        return $this->get(SelfServicePortalConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING, []);
    }

    /**
     * Specification:
     * - Returns the default sort field for file dashboard.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultFileDashboardSortField(): string
    {
        return static::DEFAULT_FILE_DASHBOARD_SORT_FIELD;
    }

    /**
     * Specification:
     * - Returns the default sort direction for file dashboard.
     *
     * @api
     *
     * @return bool
     */
    public function isDefaultFileDashboardSortAscending(): bool
    {
        return static::DEFAULT_FILE_DASHBOARD_SORT_IS_ASCENDING;
    }

    /**
     * Specification:
     * - Returns the default page number for file dashboard.
     *
     * @api
     *
     * @return int
     */
    public function getDefaultFileDashboardPageNumber(): int
    {
        return static::DEFAULT_FILE_DASHBOARD_PAGE_NUMBER;
    }

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
     * - Returns the default page number for inquiry asset expansion pagination.
     *
     * @api
     *
     * @return int
     */
    public function getInquiryAssetExpanderPageNumber(): int
    {
        return static::SSP_INQUIRY_ASSET_EXPANDER_PAGE_NUMBER;
    }

    /**
     * Specification:
     * - Returns the default max per page for inquiry asset expansion pagination.
     *
     * @api
     *
     * @return int
     */
    public function getInquiryAssetExpanderMaxPerPage(): int
    {
        return static::SSP_INQUIRY_ASSET_EXPANDER_MAX_PER_PAGE;
    }

    /**
     * Specification:
     * - Returns the default page number for inquiry dashboard.
     *
     * @api
     *
     * @return int
     */
    public function getDefaultInquiryDashboardPageNumber(): int
    {
        return static::DEFAULT_INQUIRY_DASHBOARD_PAGE_NUMBER;
    }

    /**
     * Specification:
     * - Returns the default maximum items per page for inquiry dashboard.
     *
     * @api
     *
     * @return int
     */
    public function getDefaultInquiryDashboardMaxPerPage(): int
    {
        return static::DEFAULT_INQUIRY_DASHBOARD_MAX_PER_PAGE;
    }

    /**
     * Specification:
     * - Returns the default sort field for inquiry dashboard.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultInquiryDashboardSortField(): string
    {
        return static::DEFAULT_INQUIRY_DASHBOARD_SORT_FIELD;
    }

    /**
     * Specification:
     * - Returns the default sort direction for inquiry dashboard.
     *
     * @api
     *
     * @return bool
     */
    public function isDefaultInquiryDashboardSortAscending(): bool
    {
        return static::DEFAULT_INQUIRY_DASHBOARD_SORT_IS_ASCENDING;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getMaxFileSize(): string
    {
        return static::DEFAULT_MAX_FILE_SIZE;
    }

    /**
     * @api
     *
     * @return list<string>
     */
    public function getAllowedMimeTypes(): array
    {
        return static::DEFAULT_ALLOWED_MIME_TYPES;
    }

    /**
     * @api
     *
     * @return list<string>
     */
    public function getAllowedFileExtensions(): array
    {
        return static::DEFAULT_ALLOWED_FILE_EXTENSIONS;
    }

    /**
     * @api
     *
     * @return list<string>
     */
    public function getEntityTypes(): array
    {
        return $this->getSharedConfig()->getEntityTypes();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getFileSequenceNumberPrefix(): string
    {
        return static::FILE_REFERENCE_PREFIX;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getFileSequenceNumberName(): string
    {
        return static::FILE_REFERENCE_NAME;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDateTimeZone(): string
    {
        return $this->getSharedConfig()->getDateTimeZone();
    }

    /**
     * Specification:
     * - Returns the storage name for file upload operations.
     * - Used to identify the storage provider for uploaded files in the file management system.
     *
     * @api
     *
     * @return string
     */
    public function getFileUploadStorageName(): string
    {
        return $this->get(SelfServicePortalConstants::STORAGE_NAME);
    }

    /**
     * Specification:
     * - Returns the maximum number of results for file attachment form autocomplete searches.
     * - Used to limit company, company user, and company business unit autocomplete results in file attachment forms.
     * - Applied when searching for entities to attach files to.
     *
     * @api
     *
     * @return int
     */
    public function getFileAttachmentFormAutocompleteLimit(): int
    {
        return static::DEFAULT_FILE_ATTACHMENT_FORM_AUTOCOMPLETE_LIMIT;
    }

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
    public function getInquirySequenceNumberSettings(string $storeName): SequenceNumberSettingsTransfer
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
    public function getAllSelectableInquiryTypes(): array
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
    public function getInquiryStateMachineName(): string
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
    public function getInquiryStateMachineProcessInquiryTypeMap(): array
    {
        return $this->getSharedConfig()->getInquiryStateMachineProcessInquiryTypeMap();
    }

    /**
     * Specification:
     * - Returns the ssp inquiry state machine process => initial state map.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getInquiryInitialStateMap(): array
    {
        return $this->getSharedConfig()->getInquiryInitialStateMap();
    }

    /**
     * Specification:
     * - Returns exact ssp inquiry event name for ssp inquiry cancellation.
     *
     * @api
     *
     * @return string
     */
    public function getInquiryCancelStateMachineEventName(): string
    {
        return $this->getSharedConfig()->getInquiryCancelStateMachineEventName();
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
        return $this->get(SelfServicePortalConstants::BASE_URL_YVES);
    }

    /**
     * Specification:
     * - Returns the inquiry status to СSS class name mapping.
     * - Used for status indicator styling.
     *
     * @api
     *
     * @return array<string>
     */
    public function getInquiryStatusClassMap(): array
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
    public function getInquiryPendingStatus(): ?string
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
    public function getInquiryFileStorageName(): string
    {
        return $this->getSharedConfig()->getInquiryStorageName();
    }

    /**
     * Specification:
     * - Defines the read chunk size in bytes.
     *
     * @api
     *
     * @return int
     */
    public function getInquiryFileReadChunkSize(): int
    {
        return $this->getSharedConfig()->getInquiryFileReadChunkSize();
    }

    /**
     * Specification:
     * - Returns the maximum length of the subject field for ssp inquiry.
     *
     * @api
     *
     * @return int
     */
    public function getSubjectMaxLength(): int
    {
        return $this->getSharedConfig()->getSubjectMaxLength();
    }

    /**
     * Specification:
     * - Returns the maximum length of the description field for ssp inquiry.
     *
     * @api
     *
     * @return int
     */
    public function getDescriptionMaxLength(): int
    {
        return $this->getSharedConfig()->getDescriptionMaxLength();
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
     * - Returns the allowed file extensions for file uploads during ssp inquiry creation.
     *
     * @api
     *
     * @return array<string>
     */
    public function getSspInquiryAllowedFileExtensions(): array
    {
        return $this->getSharedConfig()->getSspInquiryAllowedFileExtensions();
    }

    /**
     * Specification:
     * - Returns the settings for the ssp asset sequence number.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    public function getAssetSequenceNumberSettings(): SequenceNumberSettingsTransfer
    {
        return (new SequenceNumberSettingsTransfer())
            ->setName(static::NAME_SSP_ASSET_REFERENCE)
            ->setPrefix($this->createAssetPrefix());
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
        return $this->getSharedConfig()->getAssetStatuses();
    }

    /**
     * Specification:
     * - Returns the initial asset status.
     *
     * @api
     *
     * @return string
     */
    public function getInitialAssetStatus(): string
    {
        return static::INITIAL_SSP_ASSET_STATUS;
    }

    /**
     * Specification:
     * - Defines the Storage for asset image file.
     * - A `FileSystemConstants::FILESYSTEM_SERVICE` with the same storage name must be defined.
     *
     * @api
     *
     * @return string|null
     */
    public function getAssetStorageName(): ?string
    {
        return null;
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
        return $this->getSharedConfig()->getSspAssetAllowedFileExtensions();
    }

    /**
     * Specification:
     * - Returns the allowed file mime types for file uploads during ssp asset creation/update.
     *
     * @api
     *
     * @return array<string>
     */
    public function getSspAssetAllowedFileMimeTypes(): array
    {
        return $this->getSharedConfig()->getSspAssetAllowedFileMimeTypes();
    }

    /**
     * Specification:
     * - Returns the default file max size for file uploads for ssp asset.
     *
     * @api
     *
     * @return string
     */
    public function getAssetDefaultFileMaxSize(): string
    {
        return $this->getSharedConfig()->getDefaultFileMaxSize();
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
        return $this->getSharedConfig()->getSspAssetImageReadChunkSize();
    }

    /**
     * Specification:
     * - Creates a prefix for the asset sequence number.
     *
     * @api
     *
     * @return string
     */
    protected function createAssetPrefix(): string
    {
        $sequenceNumberPrefixParts = [];
        $sequenceNumberPrefixParts[] = static::SSP_ASSET_REFERENCE_PREFIX;

        return sprintf('%s--', implode('-', $sequenceNumberPrefixParts));
    }
}
