<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Inquiry\Handler;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\SspInquirySearchFiltersForm;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\SspInquirySearchForm;
use Symfony\Component\Form\FormInterface;

class SspInquirySearchFormHandler implements SspInquirySearchFormHandlerInterface
{
    /**
     * @var string
     */
    protected const ACCESS_LEVEL_MY_INQUIRIES = 'myInquiries';

    /**
     * @var string
     */
    protected const ACCESS_LEVEL_COMPANY_INQUIRIES = 'companyInquiries';

    /**
     * @var string
     */
    protected const ORDER_DIRECTION_ASC = 'ASC';

    public function handleFormSubmit(FormInterface $sspInquirySearchForm, SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCriteriaTransfer
    {
        if (!$this->isFormSubmittedAndValid($sspInquirySearchForm)) {
            return $this->applyDefaultSorting($sspInquiryCriteriaTransfer);
        }

        $formData = $sspInquirySearchForm->getData();

        $sspInquiryCriteriaTransfer = $this->applySorting($sspInquiryCriteriaTransfer, $formData);
        $sspInquiryCriteriaTransfer = $this->applyBasicFiltering($sspInquiryCriteriaTransfer, $formData);
        $sspInquiryCriteriaTransfer = $this->applyDateFiltering($sspInquiryCriteriaTransfer, $formData);
        $sspInquiryCriteriaTransfer = $this->applySearchFiltering($sspInquiryCriteriaTransfer, $formData);
        $sspInquiryCriteriaTransfer = $this->applyAccessLevelFiltering($sspInquiryCriteriaTransfer, $formData);

        return $sspInquiryCriteriaTransfer;
    }

    protected function isFormSubmittedAndValid(FormInterface $sspInquirySearchForm): bool
    {
        return $sspInquirySearchForm->isSubmitted() && $sspInquirySearchForm->isValid();
    }

    protected function applyDefaultSorting(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCriteriaTransfer
    {
        $sspInquiryCriteriaTransfer->setSortCollection(
            (new ArrayObject([
                (new SortTransfer())
                    ->setField(SspInquiryTransfer::ID_SSP_INQUIRY)
                    ->setIsAscending(false),
            ])),
        );

        return $sspInquiryCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     * @param array<string, mixed> $formData
     *
     * @return \Generated\Shared\Transfer\SspInquiryCriteriaTransfer
     */
    protected function applySorting(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer, array $formData): SspInquiryCriteriaTransfer
    {
        $orderByField = $this->getOrderByField($formData);
        $isAscending = $this->isAscendingOrder($formData);

        $sspInquiryCriteriaTransfer->setSortCollection(
            (new ArrayObject([
                (new SortTransfer())
                    ->setField($orderByField)
                    ->setIsAscending($isAscending),
            ])),
        );

        return $sspInquiryCriteriaTransfer;
    }

    /**
     * @param array<string, mixed> $formData
     *
     * @return string
     */
    protected function getOrderByField(array $formData): string
    {
        return $formData[SspInquirySearchForm::FIELD_ORDER_BY] ?? SspInquiryTransfer::ID_SSP_INQUIRY;
    }

    /**
     * @param array<string, mixed> $formData
     *
     * @return bool
     */
    protected function isAscendingOrder(array $formData): bool
    {
        return $formData[SspInquirySearchForm::FIELD_ORDER_DIRECTION] === static::ORDER_DIRECTION_ASC;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     * @param array<string, mixed> $formData
     *
     * @return \Generated\Shared\Transfer\SspInquiryCriteriaTransfer
     */
    protected function applyBasicFiltering(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer, array $formData): SspInquiryCriteriaTransfer
    {
        $sspInquiryCriteriaTransfer->getSspInquiryConditionsOrFail()
            ->setType($formData[SspInquirySearchForm::FIELD_TYPE] ?? null)
            ->setStatus($formData[SspInquirySearchForm::FIELD_STATUS] ?? null);

        return $sspInquiryCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     * @param array<string, mixed> $formData
     *
     * @return \Generated\Shared\Transfer\SspInquiryCriteriaTransfer
     */
    protected function applyDateFiltering(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer, array $formData): SspInquiryCriteriaTransfer
    {
        $createdDateFrom = $this->getCreatedDateFrom($formData);
        $createdDateTo = $this->getCreatedDateTo($formData);

        $sspInquiryCriteriaTransfer->getSspInquiryConditionsOrFail()
            ->setCreatedDateFrom($createdDateFrom?->format(DateTime::ATOM))
            ->setCreatedDateTo($createdDateTo?->modify('+1 day')?->format(DateTime::ATOM));

        return $sspInquiryCriteriaTransfer;
    }

    /**
     * @param array<string, mixed> $formData
     *
     * @return \DateTime|null
     */
    protected function getCreatedDateFrom(array $formData): ?DateTime
    {
        return $formData[SspInquirySearchForm::FIELD_FILTERS][SspInquirySearchFiltersForm::FIELD_DATE_FROM] ?? null;
    }

    /**
     * @param array<string, mixed> $formData
     *
     * @return \DateTime|null
     */
    protected function getCreatedDateTo(array $formData): ?DateTime
    {
        return $formData[SspInquirySearchForm::FIELD_FILTERS][SspInquirySearchFiltersForm::FIELD_DATE_TO] ?? null;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     * @param array<string, mixed> $formData
     *
     * @return \Generated\Shared\Transfer\SspInquiryCriteriaTransfer
     */
    protected function applySearchFiltering(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer, array $formData): SspInquiryCriteriaTransfer
    {
        $searchString = $this->getSearchString($formData);

        if ($searchString) {
            $sspInquiryCriteriaTransfer->getSspInquiryConditionsOrFail()
                ->setSearchString($searchString);
        }

        return $sspInquiryCriteriaTransfer;
    }

    /**
     * @param array<string, mixed> $formData
     *
     * @return string|null
     */
    protected function getSearchString(array $formData): ?string
    {
        return $formData[SspInquirySearchForm::FIELD_FILTERS][SspInquirySearchFiltersForm::FIELD_SEARCH] ?? null;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     * @param array<string, mixed> $formData
     *
     * @return \Generated\Shared\Transfer\SspInquiryCriteriaTransfer
     */
    protected function applyAccessLevelFiltering(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer, array $formData): SspInquiryCriteriaTransfer
    {
        $accessLevel = $formData[SspInquirySearchForm::FIELD_FILTERS][SspInquirySearchFiltersForm::FIELD_ACCESS_LEVEL] ?? null;

        if (!$accessLevel) {
            return $sspInquiryCriteriaTransfer;
        }

        $sspInquiryOwnerConditionGroupTransfer = $sspInquiryCriteriaTransfer->getSspInquiryConditionsOrFail()->getSspInquiryOwnerConditionGroupOrFail();
        $companyUserTransfer = $sspInquiryOwnerConditionGroupTransfer->getCompanyUser();

        if (!$companyUserTransfer) {
            return $sspInquiryCriteriaTransfer;
        }

        if ($accessLevel === static::ACCESS_LEVEL_MY_INQUIRIES) {
            $sspInquiryOwnerConditionGroupTransfer
                ->setCompanyUser($companyUserTransfer)
                ->setIdCompany(null)
                ->setIdCompanyBusinessUnit(null);

            return $sspInquiryCriteriaTransfer;
        }

        if ($accessLevel === static::ACCESS_LEVEL_COMPANY_INQUIRIES) {
            $sspInquiryOwnerConditionGroupTransfer
                ->setIdCompany($companyUserTransfer->getFkCompany())
                ->setCompanyUser($companyUserTransfer)
                ->setIdCompanyBusinessUnit(null);

            return $sspInquiryCriteriaTransfer;
        }

        if (is_int($accessLevel)) {
            $sspInquiryOwnerConditionGroupTransfer
                ->setIdCompanyBusinessUnit($accessLevel)
                ->setCompanyUser($companyUserTransfer)
                ->setIdCompany(null);
        }

        return $sspInquiryCriteriaTransfer;
    }
}
