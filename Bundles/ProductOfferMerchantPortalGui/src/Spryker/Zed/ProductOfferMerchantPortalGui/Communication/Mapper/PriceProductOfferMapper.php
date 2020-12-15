<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper;

use Generated\Shared\Transfer\GuiTableEditableDataErrorTransfer;
use Generated\Shared\Transfer\GuiTableEditableInitialDataTransfer;
use Generated\Shared\Transfer\PriceProductOfferTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ValidationErrorTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;

class PriceProductOfferMapper
{
    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade)
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

        foreach ($validationErrorTransfers as $validationErrorTransfer) {
            if (!$validationErrorTransfer->getPropertyPath()) {
                continue;
            }

            $initialData = $this->addInitialDataErrors($validationErrorTransfer, $initialData);
        }

        return $initialData;
    }

    /**
     * @phpstan-param array<mixed> $initialData
     *
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\ValidationErrorTransfer $validationErrorTransfer
     * @param array $initialData
     *
     * @return array
     */
    protected function addInitialDataErrors(ValidationErrorTransfer $validationErrorTransfer, array $initialData): array
    {
        $propertyPath = $this->extractPropertyPathValues($validationErrorTransfer->getPropertyPath());

        if (!$propertyPath || !is_array($propertyPath)) {
            return $initialData;
        }

        $rowNumber = (int)$propertyPath[0] === 0 ? 0 : ((int)$propertyPath[0] - 1) % 2;
        $isRowError = count($propertyPath) < 3;
        $errorMessage = $validationErrorTransfer->getMessage();

        if ($isRowError) {
            $initialData[GuiTableEditableInitialDataTransfer::ERRORS][$rowNumber][GuiTableEditableDataErrorTransfer::ROW_ERROR] = $errorMessage;
            $initialData[GuiTableEditableInitialDataTransfer::ERRORS][$rowNumber][GuiTableEditableDataErrorTransfer::COLUMN_ERRORS][PriceProductOfferTableViewTransfer::STORE] = true;
            $initialData[GuiTableEditableInitialDataTransfer::ERRORS][$rowNumber][GuiTableEditableDataErrorTransfer::COLUMN_ERRORS][PriceProductOfferTableViewTransfer::CURRENCY] = true;

            return $initialData;
        }

        $idColumn = $this->transformPropertyPathToColumnId($propertyPath);

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
     * @param string[] $propertyPath
     *
     * @return string
     */
    protected function transformPropertyPathToColumnId(array $propertyPath): string
    {
        [$entityNumber, $entityName, $fieldName] = $propertyPath;

        if (!$entityName) {
            return '';
        }

        if ($entityName === PriceProductTransfer::MONEY_VALUE) {
            $priceTypes = $this->priceProductFacade->getPriceTypeValues();
            $priceTypeName = mb_strtolower($priceTypes[$entityNumber]->getName());

            if (!$fieldName) {
                return '';
            }

            return sprintf('%s[%s][%s]', $priceTypeName, (string)$entityName, (string)$fieldName);
        }

        return (string)$entityName;
    }
}
