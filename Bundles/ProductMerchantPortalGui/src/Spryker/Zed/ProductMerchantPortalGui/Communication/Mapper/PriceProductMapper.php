<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Laminas\Filter\StringToLower;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\PriceProductMergerInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface;

class PriceProductMapper implements PriceProductMapperInterface
{
    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_TYPE_DEFAULT
     *
     * @var string
     */
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @var string
     */
    protected const PRICE_KEY = '%s[%s][%s]';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DIMENSION_DEFAULT
     *
     * @var string
     */
    protected const PRICE_DIMENSION_TYPE_DEFAULT = 'PRICE_DIMENSION_DEFAULT';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface
     */
    protected ProductMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface
     */
    protected ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\PriceProductMergerInterface
     */
    protected PriceProductMergerInterface $priceProductMerger;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @var array<\Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\PriceProductMapperPluginInterface>
     */
    protected array $priceProductMapperPlugins;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\PriceProductMergerInterface $priceProductMerger
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param array<\Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\PriceProductMapperPluginInterface> $priceProductMapperPlugins
     */
    public function __construct(
        ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade,
        ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade,
        PriceProductMergerInterface $priceProductMerger,
        ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService,
        array $priceProductMapperPlugins
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->currencyFacade = $currencyFacade;
        $this->moneyFacade = $moneyFacade;
        $this->priceProductMerger = $priceProductMerger;
        $this->utilEncodingService = $utilEncodingService;
        $this->priceProductMapperPlugins = $priceProductMapperPlugins;
    }

    /**
     * @param array<mixed> $newPriceProducts
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function mapTableRowsToPriceProductTransfers(
        array $newPriceProducts,
        ArrayObject $priceProductTransfers
    ): ArrayObject {
        $priceTypeTransfers = $this->priceProductFacade->getPriceTypeValues();

        foreach ($newPriceProducts as $newPriceProduct) {
            $priceProductTransfers = $this->addNewPriceProductData(
                $newPriceProduct,
                $priceProductTransfers,
                $priceTypeTransfers,
            );
        }

        return $priceProductTransfers;
    }

    /**
     * @param array<string, mixed> $data
     * @param \ArrayObject<int,\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \ArrayObject<int,\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function mapRequestDataToPriceProductTransfers(
        array $data,
        ArrayObject $priceProductTransfers
    ): ArrayObject {
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $this->executePriceProductMapperPlugins($priceProductTransfer, $data);
        }

        return new ArrayObject($priceProductTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer
     */
    public function mapPriceProductTransfersToPriceProductCollectionDeleteCriteriaTransfer(
        array $priceProductTransfers,
        PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
    ): PriceProductCollectionDeleteCriteriaTransfer {
        foreach ($priceProductTransfers as $priceProductTransfer) {
            if (
                $priceProductTransfer->getPriceDimension()
                && $priceProductTransfer->getPriceDimensionOrFail()->getIdPriceProductDefault()
            ) {
                $priceProductCollectionDeleteCriteriaTransfer->addIdPriceProductDefault(
                    $priceProductTransfer->getPriceDimensionOrFail()->getIdPriceProductDefaultOrFail(),
                );
                $priceProductCollectionDeleteCriteriaTransfer->addIdPriceProductStore(
                    $priceProductTransfer->getMoneyValueOrFail()->getIdEntityOrFail(),
                );
            }
        }

        foreach ($this->priceProductMapperPlugins as $priceProductMapperPlugin) {
            $priceProductCollectionDeleteCriteriaTransfer = $priceProductMapperPlugin
                ->mapPriceProductTransfersToPriceProductCollectionDeleteCriteriaTransfer(
                    $priceProductTransfers,
                    $priceProductCollectionDeleteCriteriaTransfer,
                );
        }

        return $priceProductCollectionDeleteCriteriaTransfer;
    }

    /**
     * @param array<mixed> $requestQueryParams
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    public function mapRequestDataToPriceProductCriteriaTransfer(
        array $requestQueryParams,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): PriceProductCriteriaTransfer {
        $requestQueryParams = $this->decodeRequestQueryParams($requestQueryParams);

        $priceProductCriteriaTransfer = $priceProductCriteriaTransfer->setPriceProductStoreIds(
            $requestQueryParams[PriceProductTableViewTransfer::TYPE_PRICE_PRODUCT_STORE_IDS],
        );

        foreach ($this->priceProductMapperPlugins as $priceProductMapperPlugin) {
            $priceProductCriteriaTransfer = $priceProductMapperPlugin->mapRequestDataToPriceProductCriteriaTransfer(
                $requestQueryParams,
                $priceProductCriteriaTransfer,
            );
        }

        return $priceProductCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductTableViewTransfer $priceProductTableViewTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTableViewTransfer
     */
    public function mapPriceProductTransferToPriceProductTableViewTransfer(
        PriceProductTransfer $priceProductTransfer,
        PriceProductTableViewTransfer $priceProductTableViewTransfer
    ): PriceProductTableViewTransfer {
        $priceProductTableViewTransfer = $priceProductTableViewTransfer
            ->setIdProductAbstract($priceProductTransfer->getIdProductAbstract())
            ->setIdProductConcrete($priceProductTransfer->getIdProduct())
            ->setVolumeQuantity($priceProductTransfer->getVolumeQuantity() ?? 1)
            ->setCurrency($priceProductTransfer->getMoneyValueOrFail()->getCurrencyOrFail()->getCodeOrFail())
            ->setStore($priceProductTransfer->getMoneyValueOrFail()->getStoreOrFail()->getNameOrFail());

        foreach ($this->priceProductMapperPlugins as $priceProductMapperPlugin) {
            $priceProductTableViewTransfer = $priceProductMapperPlugin->mapPriceProductTransferToPriceProductTableViewTransfer(
                $priceProductTransfer,
                $priceProductTableViewTransfer,
            );
        }

        return $priceProductTableViewTransfer;
    }

    /**
     * @param array<mixed> $newPriceProduct
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param array<int, \Generated\Shared\Transfer\PriceTypeTransfer> $priceTypeTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function addNewPriceProductData(
        array $newPriceProduct,
        ArrayObject $priceProductTransfers,
        array $priceTypeTransfers
    ): ArrayObject {
        $currencyTransfer = $this->getCurrencyTransfer($newPriceProduct);

        foreach ($priceTypeTransfers as $priceTypeTransfer) {
            $newPriceProductTransfer = $this->getPriceProductTransfer(
                $newPriceProduct,
                $currencyTransfer,
                $priceTypeTransfer,
            );

            if (!$newPriceProductTransfer) {
                continue;
            }

            /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
            $moneyValueTransfer = $newPriceProductTransfer->getMoneyValue();
            /** @var \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer */
            $currencyTransfer = $moneyValueTransfer->getCurrency();
            /** @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer */
            $storeTransfer = $moneyValueTransfer->getStore();
            if (!$storeTransfer->getIdStore() || !$currencyTransfer->getIdCurrency()) {
                $priceProductTransfers->append($newPriceProductTransfer);

                return $priceProductTransfers;
            }

            $priceProductTransfers = $this->priceProductMerger->mergePriceProducts(
                $newPriceProductTransfer,
                $priceProductTransfers,
            );
        }

        return $priceProductTransfers;
    }

    /**
     * @param array<mixed> $newPriceProduct
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    protected function getPriceProductTransfer(
        array $newPriceProduct,
        CurrencyTransfer $currencyTransfer,
        PriceTypeTransfer $priceTypeTransfer
    ): ?PriceProductTransfer {
        $moneyValueTransfer = $this->getMoneyValueTransfer($newPriceProduct, $priceTypeTransfer, $currencyTransfer);

        if ($moneyValueTransfer === null) {
            return null;
        }

        $priceProductDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType(static::PRICE_DIMENSION_TYPE_DEFAULT);

        $priceProductTransfer = (new PriceProductTransfer())
            ->setIdProductAbstract($newPriceProduct[PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT] ?? null)
            ->setIdProduct($newPriceProduct[PriceProductTableViewTransfer::ID_PRODUCT_CONCRETE] ?? null)
            ->setFkPriceType($priceTypeTransfer->getIdPriceType())
            ->setPriceTypeName($priceTypeTransfer->getName())
            ->setPriceType($priceTypeTransfer)
            ->setMoneyValue($moneyValueTransfer)
            ->setPriceDimension($priceProductDimensionTransfer)
            ->setVolumeQuantity((int)$newPriceProduct[PriceProductTableViewTransfer::VOLUME_QUANTITY] ?: 1);

        foreach ($this->priceProductMapperPlugins as $priceProductMapperPlugin) {
            $priceProductTransfer = $priceProductMapperPlugin->mapTableDataToPriceProductTransfer(
                $newPriceProduct,
                $priceProductTransfer,
            );
        }

        return $priceProductTransfer;
    }

    /**
     * @param array<mixed> $newPriceProduct
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransfer(array $newPriceProduct): CurrencyTransfer
    {
        $idCurrency = $newPriceProduct[PriceProductTableViewTransfer::CURRENCY];

        return $idCurrency ? $this->currencyFacade->getByIdCurrency($idCurrency) : new CurrencyTransfer();
    }

    /**
     * @param array<mixed> $newPriceProduct
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     * @param string $amountType
     *
     * @return int|null
     */
    protected function extractPriceAmount(
        array $newPriceProduct,
        PriceTypeTransfer $priceTypeTransfer,
        string $amountType
    ): ?int {
        $priceTypeName = (new StringToLower())
            ->filter($priceTypeTransfer->getNameOrFail());
        $priceKey = $this->createPriceKey($priceTypeName, $amountType);

        return $newPriceProduct[$priceKey] ?
            $this->moneyFacade->convertDecimalToInteger((float)$newPriceProduct[$priceKey]) : null;
    }

    /**
     * @param string $priceTypeName
     * @param string $amountType
     *
     * @return string
     */
    protected function createPriceKey(string $priceTypeName, string $amountType): string
    {
        return sprintf(
            static::PRICE_KEY,
            $priceTypeName,
            PriceProductTransfer::MONEY_VALUE,
            $amountType,
        );
    }

    /**
     * @param array<mixed> $newPriceProduct
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    protected function getMoneyValueTransfer(
        array $newPriceProduct,
        PriceTypeTransfer $priceTypeTransfer,
        CurrencyTransfer $currencyTransfer
    ): ?MoneyValueTransfer {
        $netAmount = $this->extractPriceAmount($newPriceProduct, $priceTypeTransfer, MoneyValueTransfer::NET_AMOUNT);
        $grossAmount = $this->extractPriceAmount($newPriceProduct, $priceTypeTransfer, MoneyValueTransfer::GROSS_AMOUNT);

        if ($netAmount === null && $grossAmount === null && !$this->isDefaultPrice($priceTypeTransfer)) {
            return null;
        }

        $storeTransfer = (new StoreTransfer())
            ->setIdStore($newPriceProduct[PriceProductTableViewTransfer::STORE]);

        return (new MoneyValueTransfer())
            ->setCurrency($currencyTransfer)
            ->setStore($storeTransfer)
            ->setFkStore($newPriceProduct[PriceProductTableViewTransfer::STORE] ? (int)$newPriceProduct[PriceProductTableViewTransfer::STORE] : null)
            ->setFkCurrency($newPriceProduct[PriceProductTableViewTransfer::CURRENCY] ? (int)$newPriceProduct[PriceProductTableViewTransfer::CURRENCY] : null)
            ->setNetAmount($netAmount)
            ->setGrossAmount($grossAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     *
     * @return bool
     */
    protected function isDefaultPrice(PriceTypeTransfer $priceTypeTransfer): bool
    {
        $priceTypeName = $priceTypeTransfer->getNameOrFail();

        return $priceTypeName === static::PRICE_TYPE_DEFAULT;
    }

    /**
     * @param array<mixed> $requestQueryParams
     *
     * @return array<array<mixed>>
     */
    protected function decodeRequestQueryParams(array $requestQueryParams): array
    {
        $decodedRequestQueryParams = [];

        foreach ($requestQueryParams as $key => $encodedValue) {
            /** @var array<mixed> $decodedValue */
            $decodedValue = $this->utilEncodingService->decodeJson($encodedValue, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                continue;
            }

            $decodedRequestQueryParams[$key] = $decodedValue;
        }

        return $decodedRequestQueryParams;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param array<string, mixed> $data
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function executePriceProductMapperPlugins(
        PriceProductTransfer $priceProductTransfer,
        array $data
    ): PriceProductTransfer {
        foreach ($this->priceProductMapperPlugins as $priceProductMapperPlugin) {
            $priceProductTransfer = $priceProductMapperPlugin->mapRequestDataToPriceProductTransfer(
                $data,
                $priceProductTransfer,
            );
        }

        return $priceProductTransfer;
    }
}
