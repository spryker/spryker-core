<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableEditableDataErrorTransfer;
use Generated\Shared\Transfer\GuiTableEditableInitialDataTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductOfferTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\ValidationErrorTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;

class PriceProductOfferMapper
{
    protected const MAP_FIELD_NAMES = [
        MoneyValueTransfer::FK_STORE => PriceProductOfferTableViewTransfer::STORE,
        MoneyValueTransfer::FK_CURRENCY => PriceProductOfferTableViewTransfer::CURRENCY,
    ];

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductOfferMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->moneyFacade = $moneyFacade;
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
     * @phpstan-param array<mixed> $requestData
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param array $requestData
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function mapRequestDataToPriceProductTransfers(array $requestData, ArrayObject $priceProductTransfers)
    {
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer */
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $this->mapRequestDataToMoneyValueTransfer($requestData, $priceProductTransfer->getMoneyValueOrFail());
        }

        return $priceProductTransfers;
    }

    /**
     * @phpstan-param array<mixed> $requestData
     *
     * @param array $requestData
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    public function mapRequestDataToMoneyValueTransfer(array $requestData, MoneyValueTransfer $moneyValueTransfer): MoneyValueTransfer
    {
        foreach ($requestData as $key => $value) {
            if (strpos($key, MoneyValueTransfer::NET_AMOUNT) !== false) {
                $value = $this->convertDecimalToInteger($value);
                $moneyValueTransfer->setNetAmount($value);

                continue;
            }

            if (strpos($key, MoneyValueTransfer::GROSS_AMOUNT) !== false) {
                $value = $this->convertDecimalToInteger($value);
                $moneyValueTransfer->setGrossAmount($value);

                continue;
            }

            if ($key === MoneyValueTransfer::STORE) {
                $value = (int)$value;
                $moneyValueTransfer->setFkStore($value);
                $moneyValueTransfer->setStore((new StoreTransfer())->setIdStore($value));

                continue;
            }

            if ($key === MoneyValueTransfer::CURRENCY) {
                $value = (int)$value;
                $moneyValueTransfer->setFkCurrency($value);

                continue;
            }
        }

        return $moneyValueTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapProductOfferTransferToPriceProductTransfer(
        ProductOfferTransfer $productOfferTransfer,
        PriceProductTransfer $priceProductTransfer
    ) {
        return $priceProductTransfer->setIdProduct($productOfferTransfer->getIdProductConcrete())
            ->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setIdProductOffer($productOfferTransfer->getIdProductOffer())
            )
            ->setMoneyValue(
                (new MoneyValueTransfer())
                    ->setCurrency($productOfferTransfer->getPrices()->getIterator()->current()->getMoneyValue()->getCurrency())
                    ->setFkStore($productOfferTransfer->getPrices()->getIterator()->current()->getMoneyValue()->getFkStore())
                    ->setStore($productOfferTransfer->getPrices()->getIterator()->current()->getMoneyValue()->getStore())
                    ->setFkCurrency($productOfferTransfer->getPrices()->getIterator()->current()->getMoneyValue()->getFkCurrency())
            );
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
        $propertyPath = $this->extractPropertyPathValues((string)$validationErrorTransfer->getPropertyPath());

        if (!$propertyPath || !is_array($propertyPath)) {
            return $initialData;
        }

        $priceTypes = $this->priceProductFacade->getPriceTypeValues();
        $rowNumber = (int)$propertyPath[0] === 0 ? 0 : round(((int)$propertyPath[0] - 1) / count($priceTypes));

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
        [$rowNumber, $entityNumber, $entityName, $fieldName] = $propertyPath;

        if (!$entityName || !$fieldName) {
            return '';
        }

        if (!empty(static::MAP_FIELD_NAMES[$fieldName])) {
            return static::MAP_FIELD_NAMES[$fieldName];
        }

        if ($entityName === PriceProductTransfer::MONEY_VALUE) {
            $priceTypeName = $entityNumber;

            return sprintf('%s[%s][%s]', $priceTypeName, $entityName, (string)$fieldName);
        }

        return (string)$entityName;
    }

    /**
     * @param mixed $value
     *
     * @return int|null
     */
    protected function convertDecimalToInteger($value): ?int
    {
        if ($value === '' || $value === null) {
            return null;
        }

        return $this->moneyFacade->convertDecimalToInteger((float)$value);
    }
}
