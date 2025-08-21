<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConstants;

/**
 * @method \SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig getSharedConfig()
 */
class SelfServicePortalConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_IN_CENTER_SERVICE = 'in-center-service';

    /**
     * @api
     *
     * @uses \Spryker\Shared\ShipmentType\ShipmentTypeConfig::SHIPMENT_TYPE_DELIVERY
     *
     * @var string
     */
    public const SHIPMENT_TYPE_DELIVERY = 'delivery';

    /**
     * @api
     *
     * @var string
     */
    public const SHIPMENT_TYPE_ON_SITE_SERVICE = 'on-site-service';

    /**
     * @var string
     */
    protected const FILE_ATTACHMENT_PAGE_PARAMETER_NAME = 'page';

    /**
     * @var int
     */
    protected const FILE_ATTACHMENT_DEFAULT_MAX_PER_PAGE = 20;

    /**
     * @var list<string>
     */
    protected const ALLOWED_FILE_TYPES = [
        'pdf',
        'jpeg',
        'jpg',
        'png',
        'heic',
        'heif',
    ];

    /**
     * @var int
     */
    protected const COMPANY_FILE_DOWNLOAD_CHUNK_SIZE = 1024 * 1024;

    /**
     * @var string
     */
    protected const TEMPLATE_PATH_SERVICE_POINT_WIDGET_CONTENT = '@SelfServicePortal/views/service-point-widget-content/service-point-widget-content.twig';

    /**
     * @var string
     */
    protected const SERVICE_LIST_PAGE_PARAMETER_NAME = 'page';

    /**
     * @var int
     */
    protected const SERVICE_LIST_DEFAULT_ITEMS_PER_PAGE = 10;

    /**
     * @uses \SprykerShop\Yves\ServicePointWidget\ServicePointWidgetConfig::SEARCH_RESULT_LIMIT
     *
     * @var int
     */
    protected const SEARCH_RESULT_LIMIT = 10;

    /**
     * @var array<string, string>
     */
    protected const INQUIRY_BACK_URL_TYPE_TO_PATH_MAP = [
        'order' => 'customer/order/details',
        'ssp-asset' => 'customer/ssp-asset/details',
    ];

    /**
     * @var array<string, string>
     */
    protected const INQUIRY_BACK_URL_TYPE_TO_IDENTIFIER_MAP = [
        'order' => 'id',
        'ssp-asset' => 'reference',
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
     * @var int
     */
    protected const INQUIRY_DOWNLOAD_CHUNK_SIZE = 1048576;

    /**
     * @var string
     */
    protected const ASSET_PARAM_PAGE = 'page';

    /**
     * @var string
     */
    protected const ASSET_PARAM_PER_PAGE = 'perPage';

    /**
     * @var string
     *
     * @uses \SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig::ACTION_VIEW
     */
    public const ASSET_ACTION_VIEW = 'view';

    /**
     * @var string
     *
     * @uses \SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig::ACTION_UPDATE
     */
    public const ASSET_ACTION_UPDATE = 'update';

    /**
     * @var string
     *
     * @uses \SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig::ACTION_UNASSIGN
     */
    public const ASSET_ACTION_UNASSIGN = 'unassign';

    /**
     * Specification:
     * - Returns a list of shipment type keys that require service point selection.
     *
     * @api
     *
     * @return list<string>
     */
    public function getShipmentTypeKeysRequiringServicePoint(): array
    {
        return [
            static::SHIPMENT_TYPE_IN_CENTER_SERVICE,
        ];
    }

    /**
     * Specification:
     * - Returns the path to the service point widget content template.
     * - This template is used to render the content of the service point widget in a dynamic way, when the shipment type is selected.
     *
     * @api
     *
     * @return string
     */
    public function getServicePointWidgetContentTemplatePath(): string
    {
        return static::TEMPLATE_PATH_SERVICE_POINT_WIDGET_CONTENT;
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
     * Specification:
     * - Returns the scheduled product class name.
     *
     * @api
     *
     * @return string
     */
    public function getScheduledProductClassName(): string
    {
        return $this->getSharedConfig()->getScheduledProductClassName();
    }

    /**
     * Specification:
     * - Returns the shipment type keys in the order they should be displayed.
     * - Shipment types not in this list will be displayed after the ones in this list.
     *
     * @api
     *
     * @return list<string>
     */
    public function getShipmentTypeSortOrder(): array
    {
        return [
            static::SHIPMENT_TYPE_DELIVERY,
            static::SHIPMENT_TYPE_IN_CENTER_SERVICE,
            static::SHIPMENT_TYPE_ON_SITE_SERVICE,
        ];
    }

    /**
     * Specification:
     * - Returns the page parameter name for service list pagination.
     *
     * @api
     *
     * @return string
     */
    public function getServiceListPageParameterName(): string
    {
        return static::SERVICE_LIST_PAGE_PARAMETER_NAME;
    }

    /**
     * Specification:
     * - Returns the default number of items per page for service list.
     *
     * @api
     *
     * @return int
     */
    public function getServiceListDefaultItemsPerPage(): int
    {
        return static::SERVICE_LIST_DEFAULT_ITEMS_PER_PAGE;
    }

    /**
     * Specification:
     * - Defines number of search results returned in single batch.
     * - Used as a fallback.
     *
     * @api
     *
     * @return int
     */
    public function getSearchResultLimit(): int
    {
        return static::SEARCH_RESULT_LIMIT;
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
     * - Returns allowed file types.
     *
     * @api
     *
     * @return list<string>
     */
    public function getCompanyFilesAllowedFileTypes(): array
    {
        return static::ALLOWED_FILE_TYPES;
    }

    /**
     * Specification:
     * - Defines the download chunk size in bytes.
     *
     * @api
     *
     * @return int
     */
    public function getCompanyFileDownloadChunkSize(): int
    {
        return static::COMPANY_FILE_DOWNLOAD_CHUNK_SIZE;
    }

    /**
     * Specification:
     * - Returns parameter name for file attachment page number.
     *
     * @api
     *
     * @return string
     */
    public function getFileAttachmentPageParameterName(): string
    {
        return static::FILE_ATTACHMENT_PAGE_PARAMETER_NAME;
    }

    /**
     * Specification:
     * - Returns maximum items per page for file attachment pagination.
     *
     * @api
     *
     * @return int
     */
    public function getFileAttachmentDefaultMaxPerPage(): int
    {
        return static::FILE_ATTACHMENT_DEFAULT_MAX_PER_PAGE;
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
    public function getSspInquiryParamPerPage(): string
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
    public function getSspInquiryParamPage(): string
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
    public function getSspInquiryAvailableStatuses(): array
    {
        return $this->getSharedConfig()->getSspInquiryAvailableStatuses();
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
        return $this->getSharedConfig()->getDefaultFileDashboardMaxPerPage();
    }

    /**
     * Specification:
     * - Returns url path map for back navigation at ssp inquiry creation page.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getInquiryBackUrlTypeToPathMap(): array
    {
        return static::INQUIRY_BACK_URL_TYPE_TO_PATH_MAP;
    }

    /**
     * Specification:
     * - Returns url identifier map for back navigation at ssp inquiry creation page.
     * - For example 'id' for navigation back to order detail page.
     * - If you need other identifier, you can add it to the map.
     * - For example 'sku' for navigation back to product detail page.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getInquiryBackUrlTypeToIdentifierMap(): array
    {
        return static::INQUIRY_BACK_URL_TYPE_TO_IDENTIFIER_MAP;
    }

    /**
     * Specification:
     * - Returns allowed file extensions for file upload for ssp asset.
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
     * - Returns allowed file mime types for file upload for ssp asset.
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
     * - Returns the default file max size per file upload during ssp asset creation.
     * - File size can be given with units: Kb Mb or Gb.
     *
     * @api
     *
     * @return string
     */
    public function getSspAssetImageFileMaxSize(): string
    {
        return $this->getSharedConfig()->getAssetImageDefaultFileMaxSize();
    }

    /**
     * Specification:
     * - Defines the read chunk size in bytes.
     *
     * @api
     *
     * @return int
     */
    public function getSspAssetImageDownloadChunkSize(): int
    {
        return $this->getSharedConfig()->getSspAssetImageReadChunkSize();
    }

    /**
     * Specification:
     * - Defines the read chunk size in bytes for the inquiry file download on the storefront.
     *
     * @api
     *
     * @return int
     */
    public function getInquiryFileDownloadChunkSize(): int
    {
        return static::INQUIRY_DOWNLOAD_CHUNK_SIZE;
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
     * - Returns parameter name for ssp asset page number.
     *
     * @api
     *
     * @return string
     */
    public function getSspAssetParamPage(): string
    {
        return static::ASSET_PARAM_PAGE;
    }

    /**
     * Specification:
     * - Returns parameter name for ssp assets per page.
     *
     * @api
     *
     * @return string
     */
    public function getSspAssetParamPerPage(): string
    {
        return static::ASSET_PARAM_PER_PAGE;
    }

    /**
     * Specification:
     * - Returns page size for ssp assets list page.
     *
     * @api
     *
     * @return int
     */
    public function getSspAssetCountPerPageList(): int
    {
        return 10;
    }

    /**
     * @api
     *
     * @return array<string, array<string>>
     */
    public function getSspStatusAllowedActionsMapping(): array
    {
        return $this->getSharedConfig()->getSspStatusAllowedActionsMapping();
    }

    /**
     * Returns the shipment type key for in-center service.
     *
     * @api
     *
     * @return string
     */
    public function getShipmentTypeInCenterService(): string
    {
        return static::SHIPMENT_TYPE_IN_CENTER_SERVICE;
    }

    /**
     * Specification:
     * - Returns the Google Maps API key.
     *
     * @api
     *
     * @return string
     */
    public function getGoogleMapsApiKey(): string
    {
        return $this->get(SelfServicePortalConstants::GOOGLE_MAPS_API_KEY);
    }

    /**
     * Specification:
     * - Returns the shipment type keys that are applicable for single address per shipment type checkbox.
     * - Only these shipment types will show the checkbox option in multi-shipping address forms.
     *
     * @api
     *
     * @return list<string>
     */
    public function getApplicableShipmentTypesForSingleAddressPerShipmentType(): array
    {
        return [
            static::SHIPMENT_TYPE_DELIVERY,
            static::SHIPMENT_TYPE_ON_SITE_SERVICE,
        ];
    }

    /**
     * Specification:
     * - Returns the shipment type keys that behave like delivery.
     * - These shipment types will be treated the same as delivery in business logic.
     * - Override this method in project-level configuration to define delivery-like shipment types.
     *
     * @api
     *
     * @return list<string>
     */
    public function getDeliveryLikeShipmentTypes(): array
    {
        return [];
    }
}
