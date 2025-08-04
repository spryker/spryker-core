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
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Exception\AssetImageFileUploadStorageNameNotConfiguredException;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Exception\CompanyFileUploadStorageNameNotConfiguredException;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Exception\InquiryFileUploadStorageNameNotConfiguredException;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Exception\InquiryPendingStatusNotConfiguredException;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Exception\DefaultMerchantNotConfiguredException;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Exception\SspModelImageFileStorageNameIsNotConfigured;

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
     * - Import type for product class.
     *
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_PRODUCT_CLASS = 'product-class';

    /**
     * Specification:
     * - Import type for product to product class relation.
     *
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_PRODUCT_TO_PRODUCT_CLASS = 'product-to-product-class';

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
    protected const DEFAULT_COMPANY_FILE_MAX_FILE_SIZE = '100M';

    /**
     * @var array<string>
     */
    protected const DEFAULT_COMPANY_FILE_ALLOWED_MIME_TYPES = [
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
    protected const DEFAULT_COMPANY_FILE_ALLOWED_FILE_EXTENSIONS = [
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
     * @var string
     */
    protected const NAME_SSP_MODEL_REFERENCE = 'SspModelReference';

    /**
     * @var string
     */
    protected const SSP_MODEL_REFERENCE_PREFIX = 'MDL';

    /**
     * @var int
     */
    protected const SSP_MODEL_NAME_MAX_LENGTH = 255;

    /**
     * @var int
     */
    protected const SSP_MODEL_CODE_MAX_LENGTH = 100;

    /**
     * @var string
     */
    protected const SSP_MODEL_DEFAULT_IMAGE_FILE_MAX_SIZE = '10M';

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
            ->setModuleName(static::MODULE_NAME)
            ->setDirectory('/data/data/import/common/common/');
    }

    /**
     * Specification:
     * - Import configuration for product class.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer
     */
    public function getProductClassDataImporterConfiguration(): DataImporterDataSourceConfigurationTransfer
    {
        return (new DataImporterDataSourceConfigurationTransfer())
            ->setImportType(static::IMPORT_TYPE_PRODUCT_CLASS)
            ->setFileName('product_class.csv')
            ->setModuleName(static::MODULE_NAME)
            ->setDirectory('/data/data/import/common/common/');
    }

    /**
     * Specification:
     * - Import configuration for product to product class relation.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer
     */
    public function getProductToProductClassDataImporterConfiguration(): DataImporterDataSourceConfigurationTransfer
    {
        return (new DataImporterDataSourceConfigurationTransfer())
            ->setImportType(static::IMPORT_TYPE_PRODUCT_TO_PRODUCT_CLASS)
            ->setFileName('product_to_product_class.csv')
            ->setModuleName(static::MODULE_NAME)
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
     * - Returns the service product class name.
     *
     * @api
     *
     * @return string
     */
    public function getServiceProductClassName(): string
    {
        return $this->getSharedConfig()->getServiceProductClassName();
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
     * - Returns the default maximum items per page for inquiry dashboard.
     *
     * @api
     *
     * @return int
     */
    public function getDashboardInquiryMaxPerPage(): int
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
    public function getCompanyFileMaxFileSize(): string
    {
        return static::DEFAULT_COMPANY_FILE_MAX_FILE_SIZE;
    }

    /**
     * @api
     *
     * @return list<string>
     */
    public function getCompanyFileAllowedMimeTypes(): array
    {
        return static::DEFAULT_COMPANY_FILE_ALLOWED_MIME_TYPES;
    }

    /**
     * @api
     *
     * @return list<string>
     */
    public function getCompanyFileAllowedFileExtensions(): array
    {
        return static::DEFAULT_COMPANY_FILE_ALLOWED_FILE_EXTENSIONS;
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
        return $this->getSharedConfig()->getEntityTypes();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCompanyFileSequenceNumberPrefix(): string
    {
        return static::FILE_REFERENCE_PREFIX;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCompanyFileSequenceNumberName(): string
    {
        return static::FILE_REFERENCE_NAME;
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
        return $this->getSharedConfig()->getDateTimeZone();
    }

    /**
     * Specification:
     * - Returns the storage name for file upload operations.
     * - Used to identify the storage provider for uploaded files in the file management system.
     *
     * @api
     *
     * @throws \SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Exception\CompanyFileUploadStorageNameNotConfiguredException
     *
     * @return string
     */
    public function getCompanyFileUploadStorageName(): string
    {
        return $this->get(SelfServicePortalConstants::STORAGE_NAME) ?? throw new CompanyFileUploadStorageNameNotConfiguredException();
    }

    /**
     * Specification:
     * - Returns the storage name for inquiry file upload operations.
     * - Used to identify the storage provider for uploaded files in the file management system.
     *
     * @api
     *
     * @throws \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Exception\InquiryFileUploadStorageNameNotConfiguredException
     *
     * @return string
     */
    public function getInquiryFileUploadStorageName(): string
    {
        return $this->get(SelfServicePortalConstants::INQUIRY_STORAGE_NAME) ?? throw new InquiryFileUploadStorageNameNotConfiguredException();
    }

    /**
     * Specification:
     * - Returns the storage name for asset image file upload operations.
     * - Used to identify the storage provider for uploaded files in the file management system.
     *
     * @api
     *
     * @throws \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Exception\AssetImageFileUploadStorageNameNotConfiguredException
     *
     * @return string
     */
    public function getAssetImageFileUploadStorageName(): string
    {
        return $this->get(SelfServicePortalConstants::ASSET_STORAGE_NAME) ?? throw new AssetImageFileUploadStorageNameNotConfiguredException();
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
    public function getCompanyFileAutocompleteLimit(): int
    {
        return static::DEFAULT_FILE_ATTACHMENT_FORM_AUTOCOMPLETE_LIMIT;
    }

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
        return $this->getSharedConfig()->getSelectableInquiryTypesToInquirySourceMap();
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
    public function getSspInquiryStateMachineProcessInquiryTypeMap(): array
    {
        return $this->getSharedConfig()->getSspInquiryStateMachineProcessInquiryTypeMap();
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
        return $this->getSharedConfig()->getInquiryInitialStateMachineMap();
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
     * - Returns the asset status to СSS class name mapping.
     * - Used for status indicator styling.
     *
     * @api
     *
     * @return array<string>
     */
    public function getAssetStatusClassMap(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Returns the ssp inquiry status that is considered as "Pending".
     *
     * @api
     *
     * @throws \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Exception\InquiryPendingStatusNotConfiguredException
     *
     * @return string
     */
    public function getInquiryPendingStatus(): string
    {
        throw new InquiryPendingStatusNotConfiguredException();
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
     * - Defines the read chunk size in bytes.
     * - Used for reading inquiry files in chunks during file download in backoffice.
     *
     * @api
     *
     * @return int
     */
    public function getSspInquiryFileReadChunkSize(): int
    {
        return $this->getSharedConfig()->getSspInquiryFileReadChunkSize();
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
        return $this->getSharedConfig()->getSspInquirySubjectMaxLength();
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
        return $this->getSharedConfig()->getSspInquiryDescriptionMaxLength();
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
        return $this->getSharedConfig()->getSspInquiryFileMaxCount();
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
    public function getSspInquiryFilesMaxSize(): string
    {
        return $this->getSharedConfig()->getSspInquiriesFilesMaxSize();
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
    public function getSspInquiryFileMaxSize(): string
    {
        return $this->getSharedConfig()->getSspInquiryFileMaxSize();
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
        return $this->getSharedConfig()->getSspInquiryAllowedFileMimeTypes();
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
     * - Returns the settings for the ssp model sequence number.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    public function getModelSequenceNumberSettings(): SequenceNumberSettingsTransfer
    {
        return (new SequenceNumberSettingsTransfer())
            ->setName(static::NAME_SSP_MODEL_REFERENCE)
            ->setPrefix($this->createModelPrefix());
    }

    /**
     * Specification:
     * - Defines the Storage for model image file.
     * - A `FileSystemConstants::FILESYSTEM_SERVICE` with the same storage name must be defined.
     *
     * @api
     *
     * @throw SspModelImageFileStorageNameIsNotConfigured
     *
     * @return string
     */
    public function getModelStorageName(): string
    {
        return $this->get(SelfServicePortalConstants::SSP_MODEL_IMAGE_STORAGE_NAME) ?? throw new SspModelImageFileStorageNameIsNotConfigured();
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
    public function getSspAssetDefaultFileMaxSize(): string
    {
        return $this->getSharedConfig()->getSspAssetImageFilesMaxSize();
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

    /**
     * Specification:
     * - Creates a prefix for the model sequence number.
     *
     * @api
     *
     * @return string
     */
    protected function createModelPrefix(): string
    {
        $sequenceNumberPrefixParts = [];
        $sequenceNumberPrefixParts[] = static::SSP_MODEL_REFERENCE_PREFIX;

        return sprintf('%s--', implode('-', $sequenceNumberPrefixParts));
    }

    /**
     * Specification:
     * - Returns the maximum length for the model name.
     *
     * @api
     *
     * @return int
     */
    public function getSspModelNameMaxLength(): int
    {
        return static::SSP_MODEL_NAME_MAX_LENGTH;
    }

    /**
     * Specification:
     * - Returns the maximum length for the model code.
     *
     * @api
     *
     * @return int
     */
    public function getSspModelCodeMaxLength(): int
    {
        return static::SSP_MODEL_CODE_MAX_LENGTH;
    }

    /**
     * Specification:
     * - Returns the allowed file extensions for file uploads during ssp model creation/update.
     *
     * @api
     *
     * @return array<string>
     */
    public function getSspModelAllowedFileExtensions(): array
    {
        return ['jpg', 'jpeg', 'png'];
    }

    /**
     * Specification:
     * - Returns the allowed file mime types for file uploads during ssp model creation/update.
     *
     * @api
     *
     * @return array<string>
     */
    public function getSspModelAllowedImageFileMimeTypes(): array
    {
        return [
            'image/png',
            'image/jpeg',
            'image/jpg',
        ];
    }

    /**
     * Specification:
     * - Returns the default file max size for file uploads for ssp model.
     *
     * @api
     *
     * @return string
     */
    public function getSspModelDefaultImageFileMaxSize(): string
    {
        return static::SSP_MODEL_DEFAULT_IMAGE_FILE_MAX_SIZE;
    }
}
