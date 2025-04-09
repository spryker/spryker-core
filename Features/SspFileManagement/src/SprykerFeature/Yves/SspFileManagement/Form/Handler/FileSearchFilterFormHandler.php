<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspFileManagement\Form\Handler;

use ArrayObject;
use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentFileConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentFileSearchConditionsTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerFeature\Client\SspFileManagement\SspFileManagementClientInterface;
use SprykerFeature\Yves\SspFileManagement\Form\FileSearchFilterForm;
use SprykerFeature\Yves\SspFileManagement\Form\FileSearchFilterSubForm;
use SprykerFeature\Yves\SspFileManagement\Formatter\TimeZoneFormatterInterface;
use SprykerFeature\Yves\SspFileManagement\Reader\CompanyUserReaderInterface;
use SprykerFeature\Yves\SspFileManagement\SspFileManagementConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FileSearchFilterFormHandler implements FileSearchFilterFormHandlerInterface
{
    /**
     * @var string
     */
    protected const DEFAULT_ORDER_DIRECTION = 'ASC';

    /**
     * @param \SprykerFeature\Yves\SspFileManagement\Reader\CompanyUserReaderInterface $companyUserReader
     * @param \SprykerFeature\Client\SspFileManagement\SspFileManagementClientInterface $sspFileManagementClient
     * @param \SprykerFeature\Yves\SspFileManagement\Formatter\TimeZoneFormatterInterface $timeZoneFormatter
     * @param \SprykerFeature\Yves\SspFileManagement\SspFileManagementConfig $sspFileManagementConfig
     */
    public function __construct(
        protected CompanyUserReaderInterface $companyUserReader,
        protected SspFileManagementClientInterface $sspFileManagementClient,
        protected TimeZoneFormatterInterface $timeZoneFormatter,
        protected SspFileManagementConfig $sspFileManagementConfig
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

        $data = $request->query->all()[FileSearchFilterForm::FORM_NAME] ?? [];
        $isReset = $data[FileSearchFilterForm::FIELD_RESET] ?? null;

        if ($isReset) {
            return $this->getFileAttachmentFileCollection($fileAttachmentFileCriteriaTransfer);
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
                $this->sspFileManagementConfig->getParamPage(),
                $this->sspFileManagementConfig->getDefaultPage(),
            ))
            ->setMaxPerPage($this->sspFileManagementConfig->getDefaultMaxPerPage());
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer
     */
    protected function getFileAttachmentFileCollection(
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
    ): FileAttachmentFileCollectionTransfer {
        return $this->sspFileManagementClient->getFileAttachmentFileCollectionAccordingToPermissions($fileAttachmentFileCriteriaTransfer);
    }
}
