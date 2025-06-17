<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Handler;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use SprykerFeature\Yves\SelfServicePortal\Asset\Form\SspAssetSearchForm;
use Symfony\Component\Form\FormInterface;

class SspAssetSearchFormHandler implements SspAssetSearchFormHandlerInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface $sspAssetSearchForm
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCriteriaTransfer
     */
    public function handleSearchForm(
        FormInterface $sspAssetSearchForm,
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): SspAssetCriteriaTransfer {
        if (!$sspAssetCriteriaTransfer->getSspAssetConditions()) {
            $sspAssetCriteriaTransfer->setSspAssetConditions(new SspAssetConditionsTransfer());
        }

        $sspAssetSearchFormData = $sspAssetSearchForm->getData();

        $isAscending = ($sspAssetSearchFormData[SspAssetSearchForm::FIELD_ORDER_DIRECTION] ?? 'DESC') === 'ASC';

        $sortTransfer = (new SortTransfer())
            ->setField($sspAssetSearchFormData[SspAssetSearchForm::FIELD_ORDER_BY] ?? SspAssetTransfer::ID_SSP_ASSET)
            ->setIsAscending($isAscending);

        $sortCollection = new ArrayObject();
        $sortCollection->append($sortTransfer);

        $sspAssetCriteriaTransfer->setSortCollection($sortCollection);

        if (isset($sspAssetSearchFormData['reset']) && $sspAssetSearchFormData['reset']) {
            return $sspAssetCriteriaTransfer;
        }

        if (isset($sspAssetSearchFormData['filters']) && $sspAssetSearchFormData['filters']['scope'] === 'filterByBusinessUnit') {
            $sspAssetCriteriaTransfer->getSspAssetConditionsOrFail()->setAssignedBusinessUnitId($companyUserTransfer->getFkCompanyBusinessUnitOrFail());
        }

        $sspAssetCriteriaTransfer->getSspAssetConditionsOrFail()->setSearchText($sspAssetSearchFormData[SspAssetSearchForm::FIELD_SEARCH_TEXT] ?? null);

        return $sspAssetCriteriaTransfer;
    }
}
