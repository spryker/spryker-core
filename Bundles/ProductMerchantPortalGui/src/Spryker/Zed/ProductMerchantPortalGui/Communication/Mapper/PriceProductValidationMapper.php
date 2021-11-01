<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableEditableDataErrorTransfer;
use Generated\Shared\Transfer\GuiTableEditableInitialDataTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ValidationErrorTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Creator\PriceProductTableColumnCreatorInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Matcher\PriceProductTableRowMatcherInterface;

class PriceProductValidationMapper implements PriceProductValidationMapperInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Creator\PriceProductTableColumnCreatorInterface
     */
    protected $priceProductTableColumnCreator;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Matcher\PriceProductTableRowMatcherInterface
     */
    protected $priceProductTableRowMatcher;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Creator\PriceProductTableColumnCreatorInterface $priceProductTableColumnCreator
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Matcher\PriceProductTableRowMatcherInterface $priceProductTableRowMatcher
     */
    public function __construct(
        PriceProductTableColumnCreatorInterface $priceProductTableColumnCreator,
        PriceProductTableRowMatcherInterface $priceProductTableRowMatcher
    ) {
        $this->priceProductTableColumnCreator = $priceProductTableColumnCreator;
        $this->priceProductTableRowMatcher = $priceProductTableRowMatcher;
    }

    /**
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param array<mixed> $initialData
     *
     * @return array<mixed>
     */
    public function mapValidationResponseTransferToInitialData(
        ValidationResponseTransfer $validationResponseTransfer,
        ArrayObject $priceProductTransfers,
        array $initialData
    ): array {
        $validationErrorTransfers = $validationResponseTransfer->getValidationErrors();

        foreach ($validationErrorTransfers as $validationErrorTransfer) {
            if ($validationErrorTransfer->getPropertyPath()) {
                $initialData = $this->addValidationErrorToInitialData(
                    $validationErrorTransfer,
                    $priceProductTransfers,
                    $initialData,
                );
            }
        }

        return $initialData;
    }

    /**
     * @param \Generated\Shared\Transfer\ValidationErrorTransfer $validationErrorTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param array<mixed> $initialData
     *
     * @return array<mixed>
     */
    protected function addValidationErrorToInitialData(
        ValidationErrorTransfer $validationErrorTransfer,
        ArrayObject $priceProductTransfers,
        array $initialData
    ): array {
        $initialDataErrors = $initialData[GuiTableEditableInitialDataTransfer::ERRORS] ?? [];
        $propertyPath = $this->extractPropertyPathValues((string)$validationErrorTransfer->getPropertyPath());
        $priceProductTransfer = $this->getPriceProductForError($priceProductTransfers, $propertyPath);

        if (!$priceProductTransfer) {
            return $initialData;
        }

        $idColumn = $this->priceProductTableColumnCreator
            ->createColumnIdFromPropertyPath($priceProductTransfer, $propertyPath);

        foreach ($initialData[GuiTableEditableInitialDataTransfer::DATA] as $rowNumber => $initialDataRow) {
            if (!isset($initialDataErrors[$rowNumber])) {
                $initialDataErrors[$rowNumber] = [];
            }

            $doesRowMatch = $this->priceProductTableRowMatcher
                ->isPriceProductInRow($initialDataRow, $priceProductTransfer, $propertyPath);

            if (!$doesRowMatch) {
                continue;
            }

            $errorMessage = $validationErrorTransfer->getMessageOrFail();

            $propertyPathCount = count($propertyPath);
            if ($this->isColumnError($propertyPathCount)) {
                $initialDataErrors[$rowNumber][GuiTableEditableDataErrorTransfer::COLUMN_ERRORS][$idColumn] = $errorMessage;

                continue;
            }

            $initialDataErrors[$rowNumber][GuiTableEditableDataErrorTransfer::ROW_ERROR] = $errorMessage;
            $initialDataErrors[$rowNumber][GuiTableEditableDataErrorTransfer::COLUMN_ERRORS][PriceProductTableViewTransfer::STORE] = true;
            $initialDataErrors[$rowNumber][GuiTableEditableDataErrorTransfer::COLUMN_ERRORS][PriceProductTableViewTransfer::CURRENCY] = true;
            $initialDataErrors[$rowNumber][GuiTableEditableDataErrorTransfer::COLUMN_ERRORS][PriceProductTableViewTransfer::VOLUME_QUANTITY] = true;
        }

        $initialData[GuiTableEditableInitialDataTransfer::ERRORS] = $initialDataErrors;

        return $initialData;
    }

    /**
     * @param string $propertyPath
     *
     * @return array<string>
     */
    protected function extractPropertyPathValues(string $propertyPath): array
    {
        $propertyPath = str_replace('[', '', $propertyPath);
        $propertyPathValues = explode(']', $propertyPath);
        $propertyPathValues = array_filter($propertyPathValues, function (string $pathValue) {
            return $pathValue !== '' && $pathValue !== null;
        });

        if (!is_array($propertyPathValues)) {
            return [];
        }

        return $propertyPathValues;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param array<mixed> $propertyPath
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    protected function getPriceProductForError(
        ArrayObject $priceProductTransfers,
        array $propertyPath
    ): ?PriceProductTransfer {
        $priceProductIndex = $propertyPath[0];

        if ($priceProductTransfers->offsetExists($priceProductIndex)) {
            return $priceProductTransfers->offsetGet($priceProductIndex);
        }

        return null;
    }

    /**
     * @param int $propertyPathCount
     *
     * @return bool
     */
    protected function isColumnError(int $propertyPathCount): bool
    {
        return $propertyPathCount > 1 && $propertyPathCount !== 5;
    }
}
