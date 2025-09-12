<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form\Handler;

use ArrayObject;
use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentSearchConditionsTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface;
use SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form\DataProvider\FileSearchFilterFormDataProvider;
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

    public function __construct(
        protected CompanyUserReaderInterface $companyUserReader,
        protected SelfServicePortalClientInterface $selfServicePortalClient,
        protected TimeZoneFormatterInterface $timeZoneFormatter,
        protected SelfServicePortalConfig $selfServicePortalConfig
    ) {
    }

    public function handleSearchFormSubmit(
        Request $request,
        FormInterface $fileSearchFilterForm
    ): FileAttachmentCollectionTransfer {
        $fileAttachmentCriteriaTransfer = $this->getFileAttachmentCriteriaTransfer($request);

        $data = $request->query->all()[FileSearchFilterForm::FORM_NAME] ?? [];
        $isReset = $data[FileSearchFilterForm::FIELD_RESET] ?? null;

        if ($isReset) {
            return $this->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);
        }

        if ($request->query->has(static::QUERY_PARAM_SSP_ASSET_REFERENCE)) {
            $fileAttachmentCriteriaTransfer
                ->getFileAttachmentConditionsOrFail()->addAssetReference(
                    (string)$request->query->get(static::QUERY_PARAM_SSP_ASSET_REFERENCE),
                );
            $fileAttachmentCriteriaTransfer->setWithSspAssetRelation(true);
        }

        $fileSearchFilterForm->handleRequest($request);

        if (!$fileSearchFilterForm->isSubmitted() || !$fileSearchFilterForm->isValid()) {
            $fileAttachmentCriteriaTransfer
                ->setWithBusinessUnitRelation(true)
                ->setWithCompanyUserRelation(true)
                ->setWithSspAssetRelation(true);

            return $this->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);
        }

        $fileAttachmentCriteriaTransfer = $this->applyFormFilters(
            $fileSearchFilterForm->getData(),
            $fileAttachmentCriteriaTransfer,
        );

        return $this->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);
    }

    protected function getFileAttachmentCriteriaTransfer(Request $request): FileAttachmentCriteriaTransfer
    {
        $fileAttachmentConditionsTransfer = (new FileAttachmentConditionsTransfer())
            ->setRangeCreatedAt(new CriteriaRangeFilterTransfer());

        $sortTransfer = (new SortTransfer())
            ->setField(FileTransfer::ID_FILE)
            ->setIsAscending(false);

        return (new FileAttachmentCriteriaTransfer())
            ->setCompanyUser($this->companyUserReader->getCurrentCompanyUser())
            ->setFileAttachmentConditions($fileAttachmentConditionsTransfer)
            ->setPagination($this->getPaginationTransfer($request))
            ->setFileAttachmentSearchConditions(new FileAttachmentSearchConditionsTransfer())
            ->addSort($sortTransfer);
    }

    /**
     * @param array<string, mixed> $fileSearchFilterFormData
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer
     */
    protected function applyFormFilters(
        array $fileSearchFilterFormData,
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): FileAttachmentCriteriaTransfer {
        $filterData = $fileSearchFilterFormData[FileSearchFilterForm::FIELD_FILTERS];

        $fileTypes = $filterData[FileSearchFilterSubForm::FIELD_TYPE] ?? null;
        $searchString = $filterData[FileSearchFilterSubForm::FIELD_SEARCH] ?? null;
        $businessUnitEntity = $filterData[FileSearchFilterSubForm::FIELD_BUSINESS_ENTITY] ?? null;
        $sspAssetUnitEntity = $filterData[FileSearchFilterSubForm::FIELD_SSP_ASSET_ENTITY] ?? null;
        $dateFrom = $filterData[FileSearchFilterSubForm::FIELD_DATE_FROM] ?? null;
        $dateTo = $filterData[FileSearchFilterSubForm::FIELD_DATE_TO] ?? null;
        $orderBy = $fileSearchFilterFormData[FileSearchFilterForm::FIELD_ORDER_BY] ?? null;
        $orderDirection = $fileSearchFilterFormData[FileSearchFilterForm::FIELD_ORDER_DIRECTION] ?? static::DEFAULT_ORDER_DIRECTION;

        if ($fileTypes) {
            $fileAttachmentCriteriaTransfer->getFileAttachmentConditionsOrFail()
                ->setFileTypes([$fileTypes]);
        }

        $fileAttachmentCriteriaTransfer->getFileAttachmentSearchConditionsOrFail()
            ->setSearchString($searchString);

        $fileAttachmentCriteriaTransfer = $this->applyBusinessUnitEntityFilter(
            $businessUnitEntity,
            $fileAttachmentCriteriaTransfer,
        );
        $fileAttachmentCriteriaTransfer = $this->applySspAssetUnitEntityFilter(
            $sspAssetUnitEntity,
            $fileAttachmentCriteriaTransfer,
        );

        if ($dateFrom) {
            $fileAttachmentCriteriaTransfer->getFileAttachmentConditionsOrFail()
                ->getRangeCreatedAtOrFail()
                ->setFrom($this->timeZoneFormatter->formatToUTCFromLocalTimeZone($dateFrom));
        }

        if ($dateTo) {
            $fileAttachmentCriteriaTransfer->getFileAttachmentConditionsOrFail()
                ->getRangeCreatedAtOrFail()
                ->setTo($this->timeZoneFormatter->formatToUTCFromLocalTimeZone($dateTo));
        }

        if ($orderBy) {
            $sortTransfer = (new SortTransfer())
                ->setField($orderBy)
                ->setIsAscending($orderDirection === static::DEFAULT_ORDER_DIRECTION);

            $fileAttachmentCriteriaTransfer->setSortCollection(new ArrayObject([$sortTransfer]));
        }

        return $fileAttachmentCriteriaTransfer;
    }

    protected function getPaginationTransfer(Request $request): PaginationTransfer
    {
        return (new PaginationTransfer())
            ->setPage($request->query->getInt(
                $this->selfServicePortalConfig->getFileAttachmentPageParameterName(),
                static::FILE_ATTACHMENT_DEFAULT_PAGE,
            ))
            ->setMaxPerPage($this->selfServicePortalConfig->getFileAttachmentDefaultMaxPerPage());
    }

    protected function getFileAttachmentCollection(
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): FileAttachmentCollectionTransfer {
        return $this->selfServicePortalClient->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);
    }

    protected function applyBusinessUnitEntityFilter(
        ?string $businessUnitEntity,
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): FileAttachmentCriteriaTransfer {
        if (!$businessUnitEntity) {
            return $fileAttachmentCriteriaTransfer;
        }

        if (!$fileAttachmentCriteriaTransfer->getFileAttachmentConditions()) {
            $fileAttachmentCriteriaTransfer->setFileAttachmentConditions(new FileAttachmentConditionsTransfer());
        }

        match ($businessUnitEntity) {
            FileSearchFilterFormDataProvider::FILE_ATTACHMENT_TYPE_COMPANY_USER => $fileAttachmentCriteriaTransfer->setWithCompanyUserRelation(true),
            FileSearchFilterFormDataProvider::FILE_ATTACHMENT_TYPE_COMPANY => $fileAttachmentCriteriaTransfer
                ->setWithBusinessUnitRelation(true)
                ->setWithCompanyUserRelation(true),
            FileSearchFilterFormDataProvider::FILE_ATTACHMENT_TYPE_NONE => $fileAttachmentCriteriaTransfer,
            FileSearchFilterFormDataProvider::FILE_ATTACHMENT_TYPE_ALL => $fileAttachmentCriteriaTransfer
                ->setWithCompanyUserRelation(true)
                ->setWithBusinessUnitRelation(true),
            default =>
            $fileAttachmentCriteriaTransfer
                ->setWithBusinessUnitRelation(true),
        };

        if (
            !in_array($businessUnitEntity, [
            FileSearchFilterFormDataProvider::FILE_ATTACHMENT_TYPE_COMPANY_USER,
            FileSearchFilterFormDataProvider::FILE_ATTACHMENT_TYPE_COMPANY,
            FileSearchFilterFormDataProvider::FILE_ATTACHMENT_TYPE_NONE,
            FileSearchFilterFormDataProvider::FILE_ATTACHMENT_TYPE_ALL,
            ])
        ) {
            $fileAttachmentCriteriaTransfer
                ->getFileAttachmentConditionsOrFail()
                ->addBusinessUnitUuid($businessUnitEntity);
        }

        return $fileAttachmentCriteriaTransfer;
    }

    protected function applySspAssetUnitEntityFilter(
        ?string $sspAssetEntity,
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): FileAttachmentCriteriaTransfer {
        if (!$sspAssetEntity) {
            return $fileAttachmentCriteriaTransfer;
        }

        if (!$fileAttachmentCriteriaTransfer->getFileAttachmentConditions()) {
            $fileAttachmentCriteriaTransfer->setFileAttachmentConditions(new FileAttachmentConditionsTransfer());
        }

        match ($sspAssetEntity) {
            FileSearchFilterFormDataProvider::FILE_ATTACHMENT_TYPE_SSP_ASSET => $fileAttachmentCriteriaTransfer->setWithSspAssetRelation(true),
            FileSearchFilterFormDataProvider::FILE_ATTACHMENT_TYPE_NONE => $fileAttachmentCriteriaTransfer,
            FileSearchFilterFormDataProvider::FILE_ATTACHMENT_TYPE_ALL => $fileAttachmentCriteriaTransfer
                ->setWithSspAssetRelation(true),
            default => $fileAttachmentCriteriaTransfer
                ->setWithSspAssetRelation(true),
        };

        if (
            !in_array($sspAssetEntity, [
                FileSearchFilterFormDataProvider::FILE_ATTACHMENT_TYPE_SSP_ASSET,
                FileSearchFilterFormDataProvider::FILE_ATTACHMENT_TYPE_NONE,
                FileSearchFilterFormDataProvider::FILE_ATTACHMENT_TYPE_ALL,
            ])
        ) {
            $fileAttachmentCriteriaTransfer
                ->getFileAttachmentConditionsOrFail()
                ->addAssetReference($sspAssetEntity);
        }

        return $fileAttachmentCriteriaTransfer;
    }
}
