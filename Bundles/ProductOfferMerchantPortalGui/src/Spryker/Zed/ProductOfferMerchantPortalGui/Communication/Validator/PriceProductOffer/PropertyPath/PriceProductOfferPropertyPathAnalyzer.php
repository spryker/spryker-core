<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PropertyPath;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column\ColumnIdCreatorInterface;

class PriceProductOfferPropertyPathAnalyzer implements PriceProductOfferPropertyPathAnalyzerInterface
{
    /**
     * @uses \Spryker\Shared\PriceProductOfferVolume\PriceProductOfferVolumeConfig::VOLUME_PRICE_TYPE
     *
     * @var string
     */
    protected const VOLUME_PRICE_TYPE = 'volume_prices';

    /**
     * @var int
     */
    protected const PROPERTY_PATH_VALUES_INDEX_PRICE_PRODUCT_OFFER_INDEX = 1;

    /**
     * @var int
     */
    protected const PROPERTY_PATH_VALUES_INDEX_PRICES = 3;

    /**
     * @var int
     */
    protected const PROPERTY_PATH_VALUES_INDEX_PRICE_PRODUCT_INDEX = 4;

    /**
     * @var int
     */
    protected const PROPERTY_PATH_VALUES_INDEX_PRICE_PRODUCT = 5;

    /**
     * @var int
     */
    protected const PROPERTY_PATH_VALUES_INDEX_MONEY_VALUE = 6;

    /**
     * @var int
     */
    protected const PROPERTY_PATH_VALUES_INDEX_VOLUME_PRICE_TYPE = 7;

    /**
     * @var int
     */
    protected const PROPERTY_PATH_VALUES_INDEX_VOLUME_PRICE_INDEX = 8;

    /**
     * @var int
     */
    protected const BASE_PRICE_VIOLATION_PROPERTY_PATH_VALUES_NUMBER = 5;

    /**
     * @var int
     */
    protected const VOLUME_PRICE_ROW_ERROR_PROPERTY_PATH_VALUES_NUMBER = 9;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column\ColumnIdCreatorInterface
     */
    protected ColumnIdCreatorInterface $columnIdCreator;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column\ColumnIdCreatorInterface $columnIdCreator
     */
    public function __construct(ColumnIdCreatorInterface $columnIdCreator)
    {
        $this->columnIdCreator = $columnIdCreator;
    }

    /**
     * @param string $propertyPath
     *
     * @return bool
     */
    public function isRowViolation(string $propertyPath): bool
    {
        $propertyPathValues = $this->extractPropertyPathValues($propertyPath);
        $lastPropertyPathElement = end($propertyPathValues);

        return is_numeric($lastPropertyPathElement);
    }

    /**
     * @param string $propertyPath
     *
     * @return bool
     */
    public function isVolumePriceViolation(string $propertyPath): bool
    {
        $propertyPathValues = $this->extractPropertyPathValues($propertyPath);

        return ($propertyPathValues[static::PROPERTY_PATH_VALUES_INDEX_VOLUME_PRICE_TYPE] ?? null) === static::VOLUME_PRICE_TYPE;
    }

    /**
     * @param string $propertyPath
     *
     * @return bool
     */
    public function isBasePriceViolation(string $propertyPath): bool
    {
        $propertyPathValues = $this->extractPropertyPathValues($propertyPath);

        return (
            count($propertyPathValues) === static::BASE_PRICE_VIOLATION_PROPERTY_PATH_VALUES_NUMBER
            && $propertyPathValues[static::PROPERTY_PATH_VALUES_INDEX_PRICES] === ProductOfferTransfer::PRICES
        );
    }

    /**
     * @param string $propertyPath
     *
     * @return bool
     */
    public function isPriceRowError(string $propertyPath): bool
    {
        return $this->isBasePriceViolation($propertyPath) || $this->isVolumePriceRowError($propertyPath);
    }

    /**
     * @param string $propertyPath
     *
     * @return bool
     */
    public function isVolumePriceRowError(string $propertyPath): bool
    {
        $propertyPathValues = $this->extractPropertyPathValues($propertyPath);

        return (
            count($propertyPathValues) === static::VOLUME_PRICE_ROW_ERROR_PROPERTY_PATH_VALUES_NUMBER
            && $propertyPathValues[static::PROPERTY_PATH_VALUES_INDEX_VOLUME_PRICE_TYPE] === static::VOLUME_PRICE_TYPE
        );
    }

    /**
     * @param string $propertyPath
     *
     * @return string
     */
    public function transformPropertyPathToColumnId(string $propertyPath): string
    {
        $propertyPathValues = $this->extractPropertyPathValues($propertyPath);

        $isVolumePriceError = $this->isVolumePriceViolation($propertyPath);
        if ($isVolumePriceError) {
            $fieldName = $this->mapVolumePricePathToFieldName($propertyPathValues);
            if ($fieldName !== null) {
                return $fieldName;
            }
        }

        $columnId = $this->extractColumnId($propertyPathValues);
        if ($columnId !== null) {
            return $columnId;
        }

        return (string)end($propertyPathValues);
    }

    /**
     * @param string $propertyPath
     *
     * @return int
     */
    public function getPriceProductOfferIndex(string $propertyPath): int
    {
        $propertyPathValues = $this->extractPropertyPathValues($propertyPath);

        return (int)$propertyPathValues[static::PROPERTY_PATH_VALUES_INDEX_PRICE_PRODUCT_OFFER_INDEX];
    }

    /**
     * @param string $propertyPath
     *
     * @return int
     */
    public function getPriceProductIndex(string $propertyPath): int
    {
        $propertyPathValues = $this->extractPropertyPathValues($propertyPath);

        return (int)$propertyPathValues[static::PROPERTY_PATH_VALUES_INDEX_PRICE_PRODUCT_INDEX];
    }

    /**
     * @param string $propertyPath
     *
     * @return int
     */
    public function getVolumePriceIndex(string $propertyPath): int
    {
        $propertyPathValues = $this->extractPropertyPathValues($propertyPath);

        return (int)$propertyPathValues[static::PROPERTY_PATH_VALUES_INDEX_VOLUME_PRICE_INDEX];
    }

    /**
     * @param array<string> $propertyPathValues
     *
     * @return string|null
     */
    protected function extractColumnId(array $propertyPathValues): ?string
    {
        $fieldName = end($propertyPathValues);

        switch ($fieldName) {
            case PriceProductTransfer::VOLUME_QUANTITY:
                return $this->columnIdCreator->createVolumeQuantityColumnId();
            case MoneyValueTransfer::FK_STORE:
                return $this->columnIdCreator->createStoreColumnId();
            case MoneyValueTransfer::FK_CURRENCY:
                return $this->columnIdCreator->createCurrencyColumnId();
        }

        $priceProductTransferProperty = $propertyPathValues[static::PROPERTY_PATH_VALUES_INDEX_PRICE_PRODUCT] ?? null;
        $subTransferProperty = $propertyPathValues[static::PROPERTY_PATH_VALUES_INDEX_MONEY_VALUE] ?? null;

        if (
            $priceProductTransferProperty
            && stripos($priceProductTransferProperty, PriceProductTransfer::MONEY_VALUE) !== false
            && ($subTransferProperty === MoneyValueTransfer::GROSS_AMOUNT || $subTransferProperty === MoneyValueTransfer::NET_AMOUNT)
        ) {
            $priceTypeName = $this->extractPriceTypeNameFromMoneyValue($priceProductTransferProperty);

            return $this->columnIdCreator->createPriceKey($priceTypeName, (string)$fieldName);
        }

        return null;
    }

    /**
     * @param string $moneyTypeWithPriceTypeName
     *
     * @return string
     */
    protected function extractPriceTypeNameFromMoneyValue(string $moneyTypeWithPriceTypeName): string
    {
        [$_, $priceTypeName] = explode(':', $moneyTypeWithPriceTypeName);

        return $priceTypeName;
    }

    /**
     * @param array<string> $propertyPathValues
     *
     * @return string|null
     */
    protected function mapVolumePricePathToFieldName(
        array $propertyPathValues
    ): ?string {
        $fieldName = end($propertyPathValues);

        if ($fieldName && $this->isPriceColumn($fieldName)) {
            $priceProductTransferProperty = $propertyPathValues[static::PROPERTY_PATH_VALUES_INDEX_PRICE_PRODUCT] ?? '';
            $priceTypeName = $this->extractPriceTypeNameFromMoneyValue($priceProductTransferProperty);

            return $this->columnIdCreator->createPriceKey($priceTypeName, $fieldName);
        }

        return null;
    }

    /**
     * @param string $propertyPath
     *
     * @return array<string>
     */
    protected function extractPropertyPathValues(string $propertyPath): array
    {
        $propertyPath = trim($propertyPath, '[]');
        $propertyPathValues = explode('][', $propertyPath);

        return $propertyPathValues;
    }

    /**
     * @param string $fieldName
     *
     * @return bool
     */
    protected function isPriceColumn(string $fieldName): bool
    {
        return (
            $fieldName === MoneyValueTransfer::GROSS_AMOUNT
            || $fieldName === MoneyValueTransfer::NET_AMOUNT
        );
    }
}
