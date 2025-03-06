<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Handler;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use SprykerFeature\Yves\SspInquiryManagement\Form\SspInquirySearchFiltersForm;
use SprykerFeature\Yves\SspInquiryManagement\Form\SspInquirySearchForm;
use Symfony\Component\Form\FormInterface;

class SspInquirySearchFormHandler implements SspInquirySearchFormHandlerInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface $sspInquirySearchForm
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCriteriaTransfer
     */
    public function handleFormSubmit(FormInterface $sspInquirySearchForm, SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCriteriaTransfer
    {
        if (!$sspInquirySearchForm->isSubmitted() || !$sspInquirySearchForm->isValid()) {
             $sspInquiryCriteriaTransfer->setSortCollection(
                 (new ArrayObject([
                    (new SortTransfer())
                        ->setField(SspInquiryTransfer::ID_SSP_INQUIRY)
                        ->setIsAscending(false),
                 ])),
             );

            return $sspInquiryCriteriaTransfer;
        }

         $sspInquirySearchFormData = $sspInquirySearchForm->getData();

         $sspInquiryCriteriaTransfer->setSortCollection(
             (new ArrayObject([
                (new SortTransfer())
                    ->setField($sspInquirySearchFormData[SspInquirySearchForm::FIELD_ORDER_BY] ?? SspInquiryTransfer::ID_SSP_INQUIRY)
                    ->setIsAscending($sspInquirySearchFormData[SspInquirySearchForm::FIELD_ORDER_DIRECTION] === 'ASC'),
             ])),
         );

        /** @var \DateTime|null $createdDateFrom */
        $createdDateFrom = $sspInquirySearchFormData[SspInquirySearchForm::FIELD_FILTERS][SspInquirySearchFiltersForm::FIELD_DATE_FROM] ?? null;

        /** @var \DateTime|null $createdDateTo */
        $createdDateTo = $sspInquirySearchFormData[SspInquirySearchForm::FIELD_FILTERS][SspInquirySearchFiltersForm::FIELD_DATE_TO] ?? null;

         $sspInquiryCriteriaTransfer->getSspInquiryConditionsOrFail()
                ->setType($sspInquirySearchFormData[SspInquirySearchForm::FIELD_TYPE] ?? null)
                ->setStatus($sspInquirySearchFormData[SspInquirySearchForm::FIELD_STATUS] ?? null)
                ->setCreatedDateFrom($createdDateFrom?->format(DateTime::ATOM))
                ->setCreatedDateTo($createdDateTo?->modify('+1 day')?->format(DateTime::ATOM));

        return $sspInquiryCriteriaTransfer;
    }
}
