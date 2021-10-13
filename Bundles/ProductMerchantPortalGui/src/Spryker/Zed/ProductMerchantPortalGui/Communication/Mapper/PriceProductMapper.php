<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
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

class PriceProductMapper implements PriceProductMapperInterface
{
    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_TYPE_DEFAULT
     * @var string
     */
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @var string
     */
    protected const PRICE_KEY = '%s[%s][%s]';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DIMENSION_DEFAULT
     * @var string
     */
    protected const PRICE_DIMENSION_TYPE_DEFAULT = 'PRICE_DIMENSION_DEFAULT';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\PriceProductMergerInterface
     */
    protected $priceProductMerger;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\PriceProductMergerInterface $priceProductMerger
     */
    public function __construct(
        ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade,
        ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade,
        PriceProductMergerInterface $priceProductMerger
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->currencyFacade = $currencyFacade;
        $this->moneyFacade = $moneyFacade;
        $this->priceProductMerger = $priceProductMerger;
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
                $priceTypeTransfers
            );
        }

        return $priceProductTransfers;
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
                $priceTypeTransfer
            );

            if (!$newPriceProductTransfer) {
                continue;
            }

            $priceProductTransfers = $this->priceProductMerger->mergePriceProducts(
                $newPriceProductTransfer,
                $priceProductTransfers
            );
        }

        return $priceProductTransfers;
    }

    /**
     * @param array $newPriceProduct
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

        return (new PriceProductTransfer())
            ->setIdProductAbstract($newPriceProduct[PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT] ?? null)
            ->setIdProduct($newPriceProduct[PriceProductTableViewTransfer::ID_PRODUCT_CONCRETE] ?? null)
            ->setFkPriceType($priceTypeTransfer->getIdPriceType())
            ->setPriceType($priceTypeTransfer)
            ->setMoneyValue($moneyValueTransfer)
            ->setPriceDimension($priceProductDimensionTransfer)
            ->setVolumeQuantity($newPriceProduct[PriceProductTableViewTransfer::VOLUME_QUANTITY]);
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
            $amountType
        );
    }

    /**
     * @param array $newPriceProduct
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
            ->setFkStore((int)$newPriceProduct[PriceProductTableViewTransfer::STORE])
            ->setFkCurrency((int)$newPriceProduct[PriceProductTableViewTransfer::CURRENCY])
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
}
