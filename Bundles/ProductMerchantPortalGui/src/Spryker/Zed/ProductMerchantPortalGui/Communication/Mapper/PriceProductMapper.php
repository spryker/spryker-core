<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use Generated\Shared\Transfer\GuiTableEditableDataErrorTransfer;
use Generated\Shared\Transfer\GuiTableEditableInitialDataTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductAbstractTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ValidationErrorTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;

class PriceProductMapper
{
    protected const MAP_FIELD_NAMES = [
        MoneyValueTransfer::FK_STORE => PriceProductAbstractTableViewTransfer::STORE,
        MoneyValueTransfer::FK_CURRENCY => PriceProductAbstractTableViewTransfer::CURRENCY,
    ];

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade)
    {
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @phpstan-param array<mixed> $initialData
     *
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     * @param array $initialData
     *
     * @return array
     */
    public function mapValidationResponseTransferToInitialDataErrors(
        ValidationResponseTransfer $validationResponseTransfer,
        array $initialData
    ): array {
        $validationErrorTransfers = $validationResponseTransfer->getValidationErrors();
        $priceTypeTransfers = $this->priceProductFacade->getPriceTypeValues();

        foreach ($validationErrorTransfers as $validationErrorTransfer) {
            if (!$validationErrorTransfer->getPropertyPath()) {
                continue;
            }

            $initialData = $this->addInitialDataErrors($validationErrorTransfer, $priceTypeTransfers, $initialData);
        }

        return $initialData;
    }

    /**
     * @phpstan-param array<mixed> $initialData
     * @phpstan-param array<\Generated\Shared\Transfer\PriceTypeTransfer> $priceTypeTransfers
     *
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\ValidationErrorTransfer $validationErrorTransfer
     * @param \Generated\Shared\Transfer\PriceTypeTransfer[] $priceTypeTransfers
     * @param array $initialData
     *
     * @return array
     */
    protected function addInitialDataErrors(
        ValidationErrorTransfer $validationErrorTransfer,
        array $priceTypeTransfers,
        array $initialData
    ): array {
        $propertyPath = $this->extractPropertyPathValues($validationErrorTransfer->getPropertyPath());

        if (!$propertyPath || !is_array($propertyPath)) {
            return $initialData;
        }

        $rowNumber = (int)$propertyPath[0] === 0 ? 0 : round(((int)$propertyPath[0] - 1) / count($priceTypeTransfers));
        $isRowError = count($propertyPath) < 3;
        $errorMessage = $validationErrorTransfer->getMessage();

        if ($isRowError) {
            $initialData[GuiTableEditableInitialDataTransfer::ERRORS][$rowNumber][GuiTableEditableDataErrorTransfer::ROW_ERROR] = $errorMessage;
            $initialData[GuiTableEditableInitialDataTransfer::ERRORS][$rowNumber][GuiTableEditableDataErrorTransfer::COLUMN_ERRORS][PriceProductAbstractTableViewTransfer::STORE] = true;
            $initialData[GuiTableEditableInitialDataTransfer::ERRORS][$rowNumber][GuiTableEditableDataErrorTransfer::COLUMN_ERRORS][PriceProductAbstractTableViewTransfer::CURRENCY] = true;

            return $initialData;
        }

        $idColumn = $this->transformPropertyPathToColumnId($propertyPath, $priceTypeTransfers);

        if (!$idColumn) {
            return $initialData;
        }

        $initialData[GuiTableEditableInitialDataTransfer::ERRORS][$rowNumber][GuiTableEditableDataErrorTransfer::COLUMN_ERRORS][$idColumn] = $errorMessage;

        return $initialData;
    }

    /**
     * @param string $propertyPath
     *
     * @return string[]
     */
    protected function extractPropertyPathValues(string $propertyPath): array
    {
        $propertyPath = str_replace('[', '', $propertyPath);
        $propertyPathValues = explode(']', $propertyPath);

        if (!is_array($propertyPathValues)) {
            return [];
        }

        return $propertyPathValues;
    }

    /**
     * @phpstan-param array<\Generated\Shared\Transfer\PriceTypeTransfer> $priceTypeTransfers
     *
     * @param string[] $propertyPath
     * @param \Generated\Shared\Transfer\PriceTypeTransfer[] $priceTypeTransfers
     *
     * @return string
     */
    protected function transformPropertyPathToColumnId(array $propertyPath, array $priceTypeTransfers): string
    {
        [$entityNumber, $entityName, $fieldName] = $propertyPath;

        if (!$entityName || !$fieldName) {
            return '';
        }

        if (!empty(static::MAP_FIELD_NAMES[$fieldName])) {
            return static::MAP_FIELD_NAMES[$fieldName];
        }

        if ($entityName === PriceProductTransfer::MONEY_VALUE) {
            $priceTypeName = mb_strtolower($priceTypeTransfers[$entityNumber]->getName());

            return sprintf('%s[%s][%s]', $priceTypeName, (string)$entityName, (string)$fieldName);
        }

        return (string)$entityName;
    }
}
