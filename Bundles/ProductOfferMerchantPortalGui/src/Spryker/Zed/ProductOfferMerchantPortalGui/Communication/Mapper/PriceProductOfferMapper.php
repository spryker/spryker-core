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
use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\ValidationErrorTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Exception\PriceProductNotFoundException;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Exception\PriceProductOfferNotFoundException;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Exception\VolumePriceNotFoundException;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column\ColumnIdCreatorInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PropertyPath\PriceProductOfferPropertyPathAnalyzerInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferVolumeFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToPriceProductVolumeServiceInterface;

class PriceProductOfferMapper
{
    /**
     * @var string
     */
    protected const REQUEST_DATA_KEY_VOLUME_QUANTITY = 'volume_quantity';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferVolumeFacadeInterface
     */
    protected $priceProductOfferVolumeFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToPriceProductVolumeServiceInterface
     */
    protected $priceProductVolumeService;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PropertyPath\PriceProductOfferPropertyPathAnalyzerInterface
     */
    protected $propertyPathAnalyzer;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column\ColumnIdCreatorInterface
     */
    protected $columnIdCreator;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferVolumeFacadeInterface $priceProductOfferVolumeFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PropertyPath\PriceProductOfferPropertyPathAnalyzerInterface $propertyPathAnalyzer
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column\ColumnIdCreatorInterface $columnIdCreator
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductOfferMerchantPortalGuiToMoneyFacadeInterface $moneyFacade,
        ProductOfferMerchantPortalGuiToPriceProductOfferVolumeFacadeInterface $priceProductOfferVolumeFacade,
        ProductOfferMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService,
        PriceProductOfferPropertyPathAnalyzerInterface $propertyPathAnalyzer,
        ColumnIdCreatorInterface $columnIdCreator
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->moneyFacade = $moneyFacade;
        $this->priceProductOfferVolumeFacade = $priceProductOfferVolumeFacade;
        $this->priceProductVolumeService = $priceProductVolumeService;
        $this->propertyPathAnalyzer = $propertyPathAnalyzer;
        $this->columnIdCreator = $columnIdCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     * @param array<mixed> $initialData
     *
     * @return array<mixed>
     */
    public function mapValidationResponseTransferToInitialDataErrors(
        ValidationResponseTransfer $validationResponseTransfer,
        PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer,
        array $initialData
    ): array {
        $validationErrorTransfers = $validationResponseTransfer->getValidationErrors();

        if ($validationErrorTransfers->count() > 0) {
            $initialData = $this->initializeErrorData($initialData);
        }

        foreach ($validationErrorTransfers as $validationErrorTransfer) {
            if (!$validationErrorTransfer->getPropertyPath()) {
                continue;
            }

            $initialData = $this->addInitialDataErrors(
                $validationErrorTransfer,
                $priceProductOfferCollectionTransfer,
                $initialData,
            );
        }

        return $initialData;
    }

    /**
     * @param array<mixed> $requestData
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function mapRequestDataToPriceProductTransfers(
        array $requestData,
        ArrayObject $priceProductTransfers
    ): ArrayObject {
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $this->mapRequestDataToPriceProductTransfer($requestData, $priceProductTransfer);
        }

        return $priceProductTransfers;
    }

    /**
     * @param array<mixed> $requestData
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function mapRequestDataToPriceProductTransfer(
        array $requestData,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();
        foreach ($requestData as $key => $value) {
            if (strpos($key, static::REQUEST_DATA_KEY_VOLUME_QUANTITY) !== false) {
                $netAmount = $moneyValueTransfer->getNetAmount();
                $grossAmount = $moneyValueTransfer->getGrossAmount();
                $moneyValueTransfer->setNetAmount(null)->setGrossAmount(null);
                $priceProductTransfer
                    ->setVolumeQuantity((int)$value)
                    ->setIdPriceProduct(null)
                    ->setMoneyValue($moneyValueTransfer);
                $this->priceProductVolumeService->addVolumePrice(
                    $priceProductTransfer,
                    (new PriceProductTransfer())->setVolumeQuantity((int)$value)->setMoneyValue(
                        (new MoneyValueTransfer())->setNetAmount($netAmount)->setGrossAmount($grossAmount),
                    ),
                );

                continue;
            }

            $priceProductTransfer = $this->mapMoneyValuesToPriceProductTransfer($key, $value, $priceProductTransfer);

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

        return $priceProductTransfer->setMoneyValue($moneyValueTransfer);
    }

    /**
     * @param array<mixed> $initialData
     *
     * @return array<mixed>
     */
    protected function initializeErrorData(array $initialData): array
    {
        foreach ($initialData[GuiTableEditableInitialDataTransfer::DATA] as $index => $value) {
            $initialData[GuiTableEditableInitialDataTransfer::ERRORS][$index][GuiTableEditableDataErrorTransfer::ROW_ERROR] = null;
        }

        return $initialData;
    }

    /**
     * @param \Generated\Shared\Transfer\ValidationErrorTransfer $validationErrorTransfer
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     * @param array<mixed> $initialData
     *
     * @return array<mixed>
     */
    protected function addInitialDataErrors(
        ValidationErrorTransfer $validationErrorTransfer,
        PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer,
        array $initialData
    ): array {
        $propertyPath = $validationErrorTransfer->getPropertyPath();
        if (!$propertyPath) {
            return $initialData;
        }

        $isRowError = $this->propertyPathAnalyzer->isRowViolation($propertyPath);
        $isVolumePriceViolation = $this->propertyPathAnalyzer->isVolumePriceViolation($propertyPath);
        $idColumn = !$isRowError
            ? $this->propertyPathAnalyzer->transformPropertyPathToColumnId($propertyPath)
            : null;

        $priceProductTransfer = $this->extractInvalidPriceProduct(
            $priceProductOfferCollectionTransfer,
            $propertyPath,
            $isVolumePriceViolation,
        );

        $errorMessage = $validationErrorTransfer->getMessage();
        $initialDataErrors = $initialData[GuiTableEditableInitialDataTransfer::ERRORS] ?? [];

        foreach ($initialData[GuiTableEditableInitialDataTransfer::DATA] as $rowNumber => $initialDataRow) {
            if (!$this->isValidationMatchingPriceProduct($initialDataRow, $priceProductTransfer)) {
                continue;
            }

            if ($this->propertyPathAnalyzer->isVolumePriceRowError($propertyPath)) {
                $initialDataErrors[$rowNumber][GuiTableEditableDataErrorTransfer::ROW_ERROR] = $errorMessage;

                continue;
            }

            if ($this->propertyPathAnalyzer->isPriceRowError($propertyPath)) {
                $initialDataErrors[$rowNumber][GuiTableEditableDataErrorTransfer::ROW_ERROR] = $errorMessage;
                $initialDataErrors[$rowNumber][GuiTableEditableDataErrorTransfer::COLUMN_ERRORS][$this->columnIdCreator->createStoreColumnId()] = true;
                $initialDataErrors[$rowNumber][GuiTableEditableDataErrorTransfer::COLUMN_ERRORS][$this->columnIdCreator->createCurrencyColumnId()] = true;

                if ($isVolumePriceViolation) {
                    $initialDataErrors[$rowNumber][GuiTableEditableDataErrorTransfer::COLUMN_ERRORS][$this->columnIdCreator->createVolumeQuantityColumnId()] = true;
                }

                continue;
            }

            if ($idColumn) {
                $initialDataErrors[$rowNumber][GuiTableEditableDataErrorTransfer::COLUMN_ERRORS][$idColumn] = $errorMessage;
            }
        }

        $initialData[GuiTableEditableInitialDataTransfer::ERRORS] = $initialDataErrors;

        return $initialData;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     * @param string $propertyPath
     * @param bool $isVolumePriceViolation
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function extractInvalidPriceProduct(
        PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer,
        string $propertyPath,
        bool $isVolumePriceViolation
    ): PriceProductTransfer {
        $priceProductOfferTransfer = $this->extractMatchingPriceProductOfferFromCollection(
            $priceProductOfferCollectionTransfer,
            $propertyPath,
        );

        $priceProductTransfer = $this->extractMatchingPriceProductFromPriceProductOffer(
            $priceProductOfferTransfer,
            $propertyPath,
        );

        if (!$isVolumePriceViolation) {
            return $priceProductTransfer;
        }

        return $this->extractMatchingVolumePrice($priceProductTransfer, $propertyPath);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     * @param string $propertyPath
     *
     * @throws \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Exception\PriceProductOfferNotFoundException
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferTransfer
     */
    protected function extractMatchingPriceProductOfferFromCollection(
        PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer,
        string $propertyPath
    ): PriceProductOfferTransfer {
        $priceProductOfferIndex = $this->propertyPathAnalyzer->getPriceProductOfferIndex($propertyPath);
        if (!$priceProductOfferCollectionTransfer->getPriceProductOffers()->offsetExists($priceProductOfferIndex)) {
            throw new PriceProductOfferNotFoundException();
        }

        return $priceProductOfferCollectionTransfer
            ->getPriceProductOffers()
            ->offsetGet($priceProductOfferIndex);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferTransfer $priceProductOfferTransfer
     * @param string $propertyPath
     *
     * @throws \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Exception\PriceProductNotFoundException
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function extractMatchingPriceProductFromPriceProductOffer(
        PriceProductOfferTransfer $priceProductOfferTransfer,
        string $propertyPath
    ): PriceProductTransfer {
        $priceProductIndex = $this->propertyPathAnalyzer->getPriceProductIndex($propertyPath);
        $prices = $priceProductOfferTransfer->getProductOfferOrFail()->getPrices();

        if (!$prices->offsetExists($priceProductIndex)) {
            throw new PriceProductNotFoundException();
        }

        return $prices->offsetGet($priceProductIndex);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param string $propertyPath
     *
     * @throws \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Exception\VolumePriceNotFoundException
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function extractMatchingVolumePrice(
        PriceProductTransfer $priceProductTransfer,
        string $propertyPath
    ): PriceProductTransfer {
        $volumePriceIndex = $this->propertyPathAnalyzer->getVolumePriceIndex($propertyPath);
        $volumePrices = $this->priceProductOfferVolumeFacade
            ->extractVolumePrices([$priceProductTransfer]);

        if (!array_key_exists($volumePriceIndex, $volumePrices)) {
            throw new VolumePriceNotFoundException();
        }

        return $volumePrices[$volumePriceIndex];
    }

    /**
     * @param array<mixed> $initialDataRow
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function isValidationMatchingPriceProduct(
        array $initialDataRow,
        PriceProductTransfer $priceProductTransfer
    ): bool {
        $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();
        $volumeQuantityColumnId = $this->columnIdCreator->createVolumeQuantityColumnId();
        $isSameVolumeQuantity = (
            !$priceProductTransfer->getVolumeQuantity()
            || (int)$initialDataRow[$volumeQuantityColumnId] === $priceProductTransfer->getVolumeQuantityOrFail()
        );

        if (!$isSameVolumeQuantity) {
            return false;
        }

        $isSameStore = $initialDataRow[$this->columnIdCreator->createStoreColumnId()] == $moneyValueTransfer->getFkStore();
        $isSameCurrency = $initialDataRow[$this->columnIdCreator->createCurrencyColumnId()] == $moneyValueTransfer->getFkCurrency();
        if (!$isSameStore || !$isSameCurrency) {
            return false;
        }

        $priceTypeName = $priceProductTransfer->getPriceTypeOrFail()->getNameOrFail();

        $grossPrice = $this->convertDecimalToInteger(
            $initialDataRow[$this->columnIdCreator->createGrossAmountColumnId($priceTypeName)],
        );
        $netPrice = $this->convertDecimalToInteger(
            $initialDataRow[$this->columnIdCreator->createNetAmountColumnId($priceTypeName)],
        );

        return (
            $netPrice === $moneyValueTransfer->getNetAmount()
            && $grossPrice === $moneyValueTransfer->getGrossAmount()
        );
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

    /**
     * @param string $requestDataKey
     * @param string $requestDataValue
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapMoneyValuesToPriceProductTransfer(
        string $requestDataKey,
        string $requestDataValue,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        if (strpos($requestDataKey, MoneyValueTransfer::NET_AMOUNT) !== false) {
            $requestDataValue = $this->convertDecimalToInteger($requestDataValue);
            $priceProductTransfer->getMoneyValueOrFail()->setNetAmount($requestDataValue);
        }

        if (strpos($requestDataKey, MoneyValueTransfer::GROSS_AMOUNT) !== false) {
            $requestDataValue = $this->convertDecimalToInteger($requestDataValue);
            $priceProductTransfer->getMoneyValueOrFail()->setGrossAmount($requestDataValue);
        }

        return $priceProductTransfer;
    }
}
