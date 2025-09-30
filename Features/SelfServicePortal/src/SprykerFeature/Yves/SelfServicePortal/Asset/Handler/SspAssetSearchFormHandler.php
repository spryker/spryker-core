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
     * @var string
     */
    protected const DEFAULT_ORDER_DIRECTION = 'DESC';

    /**
     * @var string
     */
    protected const ORDER_DIRECTION_ASC = 'ASC';

    /**
     * @var string
     */
    protected const FIELD_RESET = 'reset';

    /**
     * @var string
     */
    protected const FIELD_FILTERS = 'filters';

    /**
     * @var string
     */
    protected const FIELD_SCOPE = 'scope';

    /**
     * @var string
     */
    protected const SCOPE_FILTER_BY_COMPANY = 'filterByCompany';

    public function handleSearchForm(
        FormInterface $sspAssetSearchForm,
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): SspAssetCriteriaTransfer {
        $sspAssetCriteriaTransfer = $this->ensureAssetConditionsExist($sspAssetCriteriaTransfer);

        $formData = $sspAssetSearchForm->getData();
        if ($this->isResetRequest($formData)) {
            return $sspAssetCriteriaTransfer;
        }

        $sspAssetCriteriaTransfer = $this->applySorting($sspAssetCriteriaTransfer, $formData);
        $sspAssetCriteriaTransfer = $this->applyScopeFiltering($sspAssetCriteriaTransfer, $formData);
        $sspAssetCriteriaTransfer = $this->applySearchTextFiltering($sspAssetCriteriaTransfer, $formData);

        return $sspAssetCriteriaTransfer;
    }

    protected function ensureAssetConditionsExist(SspAssetCriteriaTransfer $sspAssetCriteriaTransfer): SspAssetCriteriaTransfer
    {
        if (!$sspAssetCriteriaTransfer->getSspAssetConditions()) {
            $sspAssetCriteriaTransfer->setSspAssetConditions(new SspAssetConditionsTransfer());
        }

        return $sspAssetCriteriaTransfer;
    }

    /**
     * @param array<string, mixed> $formData
     *
     * @return bool
     */
    protected function isResetRequest(array $formData): bool
    {
        return isset($formData[static::FIELD_RESET]) && $formData[static::FIELD_RESET];
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     * @param array<string, mixed> $formData
     *
     * @return \Generated\Shared\Transfer\SspAssetCriteriaTransfer
     */
    protected function applySorting(SspAssetCriteriaTransfer $sspAssetCriteriaTransfer, array $formData): SspAssetCriteriaTransfer
    {
        $isAscending = $this->isAscendingOrder($formData);
        $orderByField = $this->getOrderByField($formData);

        $sortTransfer = (new SortTransfer())
            ->setField($orderByField)
            ->setIsAscending($isAscending);

        $sortCollection = new ArrayObject();
        $sortCollection->append($sortTransfer);

        $sspAssetCriteriaTransfer->setSortCollection($sortCollection);

        return $sspAssetCriteriaTransfer;
    }

    /**
     * @param array<string, mixed> $formData
     *
     * @return bool
     */
    protected function isAscendingOrder(array $formData): bool
    {
        $orderDirection = $formData[SspAssetSearchForm::FIELD_ORDER_DIRECTION] ?? static::DEFAULT_ORDER_DIRECTION;

        return $orderDirection === static::ORDER_DIRECTION_ASC;
    }

    /**
     * @param array<string, mixed> $formData
     *
     * @return string
     */
    protected function getOrderByField(array $formData): string
    {
        $fieldOrderBy = $formData[SspAssetSearchForm::FIELD_ORDER_BY] ?? SspAssetTransfer::ID_SSP_ASSET;

        return $fieldOrderBy === SspAssetTransfer::REFERENCE ? SspAssetTransfer::ID_SSP_ASSET : $fieldOrderBy;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     * @param array<string, mixed> $formData
     *
     * @return \Generated\Shared\Transfer\SspAssetCriteriaTransfer
     */
    protected function applyScopeFiltering(
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer,
        array $formData
    ): SspAssetCriteriaTransfer {
        if (!$this->hasScopeFilter($formData)) {
            return $sspAssetCriteriaTransfer;
        }

        $scopeValue = $formData[static::FIELD_FILTERS][static::FIELD_SCOPE];
        if ($scopeValue === static::SCOPE_FILTER_BY_COMPANY) {
            return $sspAssetCriteriaTransfer;
        }

        if (!$this->isValidBusinessUnitId($scopeValue)) {
            return $sspAssetCriteriaTransfer;
        }

        $sspAssetCriteriaTransfer->getSspAssetConditionsOrFail()
            ->setAssignedBusinessUnitId($scopeValue);

        return $sspAssetCriteriaTransfer;
    }

    /**
     * @param array<string, mixed> $formData
     *
     * @return bool
     */
    protected function hasScopeFilter(array $formData): bool
    {
        return isset($formData[static::FIELD_FILTERS][static::FIELD_SCOPE]);
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     * @param array<string, mixed> $formData
     *
     * @return \Generated\Shared\Transfer\SspAssetCriteriaTransfer
     */
    protected function applySearchTextFiltering(SspAssetCriteriaTransfer $sspAssetCriteriaTransfer, array $formData): SspAssetCriteriaTransfer
    {
        $searchText = $formData[SspAssetSearchForm::FIELD_SEARCH_TEXT] ?? null;
        $sspAssetCriteriaTransfer->getSspAssetConditionsOrFail()->setSearchText($searchText);

        return $sspAssetCriteriaTransfer;
    }

    /**
     * @param string $scopeValue
     *
     * @return bool
     */
    protected function isValidBusinessUnitId(mixed $scopeValue): bool
    {
        return (bool)$scopeValue && $scopeValue !== static::SCOPE_FILTER_BY_COMPANY;
    }
}
