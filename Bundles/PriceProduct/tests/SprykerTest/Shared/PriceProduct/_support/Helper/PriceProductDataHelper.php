<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\PriceProduct\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\PriceProductBuilder;
use Generated\Shared\DataBuilder\PriceTypeBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Spryker\Shared\PriceProduct\PriceProductConfig;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;
use Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;
use Spryker\Zed\PriceProductStorage\Business\PriceProductStorageFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class PriceProductDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @var string
     */
    protected const EUR_ISO_CODE = 'EUR';

    /**
     * @var string
     */
    protected const USD_ISO_CODE = 'USD';

    /**
     * @var int
     */
    protected const NET_PRICE = 10;

    /**
     * @var int
     */
    protected const GROSS_PRICE = 9;

    /**
     * @var string
     */
    protected const DEFAULT_CURRENT_STORE = 'DE';

    /**
     * @param array $priceProductOverride
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function havePriceProduct(array $priceProductOverride = []): PriceProductTransfer
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceProductTransfer = $this->createPriceProductTransfer(
            $priceProductOverride,
            static::NET_PRICE,
            static::GROSS_PRICE,
            static::EUR_ISO_CODE,
        );

        $priceProductTransfer = $priceProductFacade->createPriceForProduct($priceProductTransfer);

        $this->debug(sprintf(
            'Inserted Price Product Concrete: %d',
            $priceProductTransfer->getIdPriceProduct(),
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($priceProductTransfer): void {
            $this->cleanupPriceProductStore($priceProductTransfer->getIdPriceProduct());
            $this->cleanupPriceProduct($priceProductTransfer->getIdPriceProduct());
        });

        return $priceProductTransfer;
    }

    /**
     * @param int $idProductAbstract
     * @param array $priceProductOverride
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function havePriceProductAbstract(int $idProductAbstract, array $priceProductOverride = []): PriceProductTransfer
    {
        $priceProductTransfer = $this->createPriceProductTransfer(
            $priceProductOverride,
            static::NET_PRICE,
            static::GROSS_PRICE,
            static::EUR_ISO_CODE,
        );

        $this->getPriceProductFacade()->persistProductAbstractPriceCollection(
            (new ProductAbstractTransfer())->setIdProductAbstract($idProductAbstract)->addPrice($priceProductTransfer),
        );

        $this->getDataCleanupHelper()->_addCleanup(function () use ($priceProductTransfer): void {
            $this->cleanupPriceProduct($priceProductTransfer->getIdPriceProduct());
        });

        $priceProductTransfer->setIdProductAbstract($idProductAbstract);

        return $priceProductTransfer;
    }

    /**
     * @param array $priceTypeData
     *
     * @return \Generated\Shared\Transfer\PriceTypeTransfer
     */
    public function havePriceType(array $priceTypeData = []): PriceTypeTransfer
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceTypeTransfer = (new PriceTypeBuilder())
            ->seed($priceTypeData)
            ->build();

        $existingPriceTypeTransfer = $priceProductFacade->findPriceTypeByName($priceTypeTransfer->getName());

        if ($existingPriceTypeTransfer !== null) {
            return $existingPriceTypeTransfer;
        }

        $priceTypeId = $priceProductFacade->createPriceType($priceTypeTransfer->getName());

        $priceTypeTransfer->setIdPriceType($priceTypeId);

        return $priceTypeTransfer;
    }

    /**
     * @param int $fkPriceProduct
     * @param int $grossPrice
     * @param int $netPrice
     *
     * @return void
     */
    public function updatePriceProductStore(int $fkPriceProduct, int $grossPrice, int $netPrice): void
    {
        $this->getPriceProductStoreQuery()
            ->filterByFkPriceProduct($fkPriceProduct)
            ->update(['GrossPrice' => $grossPrice, 'NetPrice' => $netPrice]);
    }

    /**
     * @param int $idPriceProduct
     *
     * @return void
     */
    private function cleanupPriceProduct(int $idPriceProduct): void
    {
        $this->debug(sprintf('Deleting Price Product: %d', $idPriceProduct));

        $this->getPriceProductQueryContainer()
            ->queryPriceProduct()
            ->findByIdPriceProduct($idPriceProduct)
            ->delete();
    }

    /**
     * @param int $idPriceProduct
     *
     * @return void
     */
    private function cleanupPriceProductStore(int $idPriceProduct): void
    {
        $this->debug(sprintf('Deleting Price Product: %d', $idPriceProduct));

        $spyPriceProductStoreEntity = $this->getPriceProductStoreQuery()->findOneByFkPriceProduct($idPriceProduct);
        if ($spyPriceProductStoreEntity) {
            $spyPriceProductStoreEntity->delete();
        }
    }

    /**
     * @param array $priceProductOverride
     * @param int $netPrice
     * @param int $grossPrice
     * @param string $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductTransfer(
        array $priceProductOverride,
        int $netPrice,
        int $grossPrice,
        string $currencyIsoCode
    ): PriceProductTransfer {
        $currencyTransfer = $this->getCurrencyFacade()->fromIsoCode($currencyIsoCode);

        $config = $this->getSharedPriceProductConfig();

        $priceDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType($config->getPriceDimensionDefault());

        $defaultPriceTypeName = $this->getPriceProductFacade()->getDefaultPriceTypeName();
        $idPriceType = $this->getPriceTypeId($defaultPriceTypeName);

        $priceTypeTransfer = (new PriceTypeTransfer())
            ->setName($defaultPriceTypeName)
            ->setIdPriceType($idPriceType);

        if (isset($priceProductOverride[PriceProductTransfer::PRICE_TYPE])) {
            $priceTypeTransfer = $priceProductOverride[PriceProductTransfer::PRICE_TYPE];
        }

        if (isset($priceProductOverride[PriceProductTransfer::MONEY_VALUE][MoneyValueTransfer::NET_AMOUNT])) {
            $netPrice = $priceProductOverride[PriceProductTransfer::MONEY_VALUE][MoneyValueTransfer::NET_AMOUNT];
        }

        if (isset($priceProductOverride[PriceProductTransfer::MONEY_VALUE][MoneyValueTransfer::GROSS_AMOUNT])) {
            $grossPrice = $priceProductOverride[PriceProductTransfer::MONEY_VALUE][MoneyValueTransfer::GROSS_AMOUNT];
        }

        if (isset($priceProductOverride[PriceProductTransfer::MONEY_VALUE][MoneyValueTransfer::CURRENCY])) {
            $currencyTransfer = $priceProductOverride[PriceProductTransfer::MONEY_VALUE][MoneyValueTransfer::CURRENCY];
        }

        $priceProductDefaultData = [
            PriceProductTransfer::PRICE_TYPE_NAME => $priceTypeTransfer->getName(),
            PriceProductTransfer::PRICE_TYPE => $priceTypeTransfer,
            PriceProductTransfer::PRICE_DIMENSION => $priceDimensionTransfer,
            PriceProductTransfer::FK_PRICE_TYPE => $priceTypeTransfer->getIdPriceType(),
        ];

        $priceProductTransfer = (new PriceProductBuilder($priceProductDefaultData))
            ->seed($priceProductOverride)
            ->build();

        $storeTransfer =
            $priceProductOverride[PriceProductTransfer::MONEY_VALUE][MoneyValueTransfer::STORE]
            ?? $this->getStoreFacade()->getStoreByName(static::DEFAULT_CURRENT_STORE);

        $moneyValueTransfer = $this->createMoneyValueTransfer(
            $grossPrice,
            $netPrice,
            $storeTransfer,
            $currencyTransfer,
            $priceProductOverride[PriceProductTransfer::MONEY_VALUE] ?? [],
        );

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        return $priceProductTransfer;
    }

    /**
     * @param int $grossAmount
     * @param int $netAmount
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param array $moneyValueOverride
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function createMoneyValueTransfer(
        int $grossAmount,
        int $netAmount,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        array $moneyValueOverride = []
    ): MoneyValueTransfer {
        $moneyValueTransfer = (new MoneyValueTransfer())->fromArray($moneyValueOverride, true);

        return $moneyValueTransfer
            ->setNetAmount($netAmount)
            ->setGrossAmount($grossAmount)
            ->setFkStore($storeTransfer->getIdStore())
            ->setFkCurrency($currencyTransfer->getIdCurrency())
            ->setCurrency($currencyTransfer)
            ->setStore($storeTransfer);
    }

    /**
     * @param string $name
     *
     * @return int|null
     */
    protected function getPriceTypeId(string $name): ?int
    {
        $spyPriceTypeEntity = $this->getPriceProductQueryContainer()->queryPriceType($name)->findOne();

        if (!$spyPriceTypeEntity) {
            return null;
        }

        return $spyPriceTypeEntity->getIdPriceType();
    }

    /**
     * @return \Spryker\Shared\PriceProduct\PriceProductConfig
     */
    protected function getSharedPriceProductConfig(): PriceProductConfig
    {
        return new PriceProductConfig();
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Business\PriceProductStorageFacadeInterface
     */
    protected function getPriceProductStorageFacade(): PriceProductStorageFacadeInterface
    {
        return $this->getLocator()->priceProductStorage()->facade();
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected function getPriceProductFacade(): PriceProductFacadeInterface
    {
        return $this->getLocator()->priceProduct()->facade();
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface
     */
    protected function getPriceProductQueryContainer(): PriceProductQueryContainerInterface
    {
        return $this->getLocator()->priceProduct()->queryContainer();
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getLocator()->store()->facade();
    }

    /**
     * @return \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected function getCurrencyFacade(): CurrencyFacadeInterface
    {
        return $this->getLocator()->currency()->facade();
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    protected function getPriceProductStoreQuery(): SpyPriceProductStoreQuery
    {
        return SpyPriceProductStoreQuery::create();
    }
}
