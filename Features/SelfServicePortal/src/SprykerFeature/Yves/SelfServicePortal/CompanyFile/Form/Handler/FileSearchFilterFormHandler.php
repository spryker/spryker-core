<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form\Handler;

use ArrayObject;
use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentFileConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentFileSearchConditionsTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig as SharedSelfServicePortalConfig;
use SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form\FileSearchFilterForm;
use SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form\FileSearchFilterSubForm;
use SprykerFeature\Yves\SelfServicePortal\CompanyFile\Formatter\TimeZoneFormatterInterface;
use SprykerFeature\Yves\SelfServicePortal\Reader\CompanyUserReaderInterface;
use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FileSearchFilterFormHandler implements FileSearchFilterFormHandlerInterface
{
    /**
     * @var string
     */
    protected const DEFAULT_ORDER_DIRECTION = 'ASC';

    /**
     * @var string
     */
    protected const QUERY_PARAM_SSP_ASSET_REFERENCE = 'ssp-asset-reference';

    /**
     * @var int
     */
    protected const FILE_ATTACHMENT_DEFAULT_PAGE = 1;

    /**
     * @param \SprykerFeature\Yves\SelfServicePortal\Reader\CompanyUserReaderInterface $companyUserReader
     * @param \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface $selfServicePortalClient
     * @param \SprykerFeature\Yves\SelfServicePortal\CompanyFile\Formatter\TimeZoneFormatterInterface $timeZoneFormatter
     * @param \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig $selfServicePortalConfig
     */
    public function __construct(
        protected CompanyUserReaderInterface $companyUserReader,
        protected SelfServicePortalClientInterface $selfServicePortalClient,
        protected TimeZoneFormatterInterface $timeZoneFormatter,
        protected SelfServicePortalConfig $selfServicePortalConfig
    ) {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $fileSearchFilterForm
     *
     * @return \Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer
     */
    public function handleSearchFormSubmit(
        Request $request,
        FormInterface $fileSearchFilterForm
    ): FileAttachmentFileCollectionTransfer {
        $fileAttachmentFileCriteriaTransfer = $this->getFileAttachmentFileCriteriaTransfer($request);

        $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()->setEntityTypes([
            SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT,
            SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY,
            SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_USER,
        ]);

        $data = $request->query->all()[FileSearchFilterForm::FORM_NAME] ?? [];
        $isReset = $data[FileSearchFilterForm::FIELD_RESET] ?? null;

        if ($isReset) {
            return $this->getFileAttachmentFileCollection($fileAttachmentFileCriteriaTransfer);
        }

        if ($request->query->has(static::QUERY_PARAM_SSP_ASSET_REFERENCE)) {
            $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()->addAssetReference(
                (string)$request->query->get(static::QUERY_PARAM_SSP_ASSET_REFERENCE),
            )->setEntityTypes([SharedSelfServicePortalConfig::ENTITY_TYPE_SSP_ASSET]);
        }

        $fileSearchFilterForm->handleRequest($request);
        if (!$fileSearchFilterForm->isSubmitted() || !$fileSearchFilterForm->isValid()) {
            return $this->getFileAttachmentFileCollection($fileAttachmentFileCriteriaTransfer);
        }

        $fileAttachmentFileCriteriaTransfer = $this->applyFormFilters(
            $fileSearchFilterForm->getData(),
            $fileAttachmentFileCriteriaTransfer,
        );

        return $this->getFileAttachmentFileCollection($fileAttachmentFileCriteriaTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer
     */
    protected function getFileAttachmentFileCriteriaTransfer(Request $request): FileAttachmentFileCriteriaTransfer
    {
        $fileAttachmentFileConditionsTransfer = (new FileAttachmentFileConditionsTransfer())
            ->setRangeCreatedAt(new CriteriaRangeFilterTransfer());

        $sortTransfer = (new SortTransfer())
            ->setField(FileTransfer::ID_FILE)
            ->setIsAscending(false);

        return (new FileAttachmentFileCriteriaTransfer())
            ->setCompanyUser($this->companyUserReader->getCurrentCompanyUser())
            ->setFileAttachmentFileConditions($fileAttachmentFileConditionsTransfer)
            ->setPagination($this->getPaginationTransfer($request))
            ->setFileAttachmentFileSearchConditions(new FileAttachmentFileSearchConditionsTransfer())
            ->addSort($sortTransfer);
    }

    /**
     * @param array<string, mixed> $fileSearchFilterFormData
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer
     */
    protected function applyFormFilters(
        array $fileSearchFilterFormData,
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
    ): FileAttachmentFileCriteriaTransfer {
        $filterData = $fileSearchFilterFormData[FileSearchFilterForm::FIELD_FILTERS];

        $fileTypes = $filterData[FileSearchFilterSubForm::FIELD_TYPE] ?? null;
        $searchString = $filterData[FileSearchFilterSubForm::FIELD_SEARCH] ?? null;
        $entityTypes = $filterData[FileSearchFilterSubForm::FIELD_ACCESS_LEVEL] ?? null;
        $dateFrom = $filterData[FileSearchFilterSubForm::FIELD_DATE_FROM] ?? null;
        $dateTo = $filterData[FileSearchFilterSubForm::FIELD_DATE_TO] ?? null;
        $orderBy = $fileSearchFilterFormData[FileSearchFilterForm::FIELD_ORDER_BY] ?? null;
        $orderDirection = $fileSearchFilterFormData[FileSearchFilterForm::FIELD_ORDER_DIRECTION] ?? static::DEFAULT_ORDER_DIRECTION;

        if ($fileTypes) {
            $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()
                ->setFileTypes([$fileTypes]);
        }

        $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileSearchConditionsOrFail()
            ->setSearchString($searchString);

        if ($entityTypes) {
            $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()
                ->setEntityTypes([$entityTypes]);
        }

        if ($dateFrom) {
            $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()
                ->getRangeCreatedAtOrFail()
                ->setFrom($this->timeZoneFormatter->formatToUTCFromLocalTimeZone($dateFrom));
        }

        if ($dateTo) {
            $fileAttachmentFileCriteriaTransfer->getFileAttachmentFileConditionsOrFail()
                ->getRangeCreatedAtOrFail()
                ->setTo($this->timeZoneFormatter->formatToUTCFromLocalTimeZone($dateTo));
        }

        if ($orderBy) {
            $sortTransfer = (new SortTransfer())
                ->setField($orderBy)
                ->setIsAscending($orderDirection === static::DEFAULT_ORDER_DIRECTION);

            $fileAttachmentFileCriteriaTransfer->setSortCollection(new ArrayObject([$sortTransfer]));
        }

        return $fileAttachmentFileCriteriaTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function getPaginationTransfer(Request $request): PaginationTransfer
    {
        return (new PaginationTransfer())
            ->setPage($request->query->getInt(
                $this->selfServicePortalConfig->getFileAttachmentPageParameterName(),
                static::FILE_ATTACHMENT_DEFAULT_PAGE,
            ))
            ->setMaxPerPage($this->selfServicePortalConfig->getFileAttachmentDefaultMaxPerPage());
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer
     */
    protected function getFileAttachmentFileCollection(
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
    ): FileAttachmentFileCollectionTransfer {
        return $this->selfServicePortalClient->getFileAttachmentFileCollectionAccordingToPermissions($fileAttachmentFileCriteriaTransfer);
    }
}
