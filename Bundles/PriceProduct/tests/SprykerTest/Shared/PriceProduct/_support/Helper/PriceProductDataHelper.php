<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\PriceProduct\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\PriceProduct\PriceProductConfig;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;
use Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class PriceProductDataHelper extends Module
{
    const EUR_ISO_CODE = 'EUR';
    const USD_ISO_CODE = 'USD';
    const NET_PRICE = 10;
    const GROSS_PRICE = 9;

    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function havePriceProduct(ProductConcreteTransfer $productConcreteTransfer): PriceProductTransfer
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceProductTransfer = $priceProductFacade->createPriceForProduct(
            $this->createPriceProductTransferWithPriceType($priceProductFacade, $productConcreteTransfer)
        );

        $this->debug(sprintf(
            'Inserted Price Product Concrete: %d',
            $priceProductTransfer->getIdProduct()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($priceProductTransfer) {
            $this->cleanupPriceProductConcrete($priceProductTransfer->getIdProduct());
        });

        return $priceProductTransfer;
    }

    /**
     * @param int $idProduct
     *
     * @return void
     */
    private function cleanupPriceProductConcrete(int $idProduct): void
    {
        $this->debug(sprintf('Deleting Price Product: %d', $idProduct));

        $this->getPriceProductQueryContainer()
            ->queryPriceProduct()
            ->findByFkProduct($idProduct)
            ->delete();
    }

    /**
     * @param \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface $priceProductFacade
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductTransferWithPriceType(
        PriceProductFacadeInterface $priceProductFacade,
        ProductConcreteTransfer $productConcreteTransfer
    ): PriceProductTransfer {
        $defaultPriceTypeName = $priceProductFacade->getDefaultPriceTypeName();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($defaultPriceTypeName);

        $priceProductTransfer = $this->createPriceProductTransfer(
            $productConcreteTransfer,
            $priceTypeTransfer,
            static::NET_PRICE,
            static::GROSS_PRICE,
            self::EUR_ISO_CODE
        );

        $priceProductTransfer->setFkPriceType($this->getPriceTypeId($defaultPriceTypeName));

        return $priceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     * @param int $netPrice
     * @param int $grossPrice
     * @param string $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductTransfer(
        ProductConcreteTransfer $productConcreteTransfer,
        PriceTypeTransfer $priceTypeTransfer,
        $netPrice,
        $grossPrice,
        $currencyIsoCode
    ): PriceProductTransfer {
        $config = $this->getSharedPriceProductConfig();
        $priceDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType($config->getPriceDimensionDefault());

        $priceProductTransfer = (new PriceProductTransfer())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setSkuProductAbstract($productConcreteTransfer->getAbstractSku())
            ->setSkuProduct($productConcreteTransfer->getSku())
            ->setPriceTypeName($this->getPriceProductFacade()->getDefaultPriceTypeName())
            ->setPriceType($priceTypeTransfer)
            ->setPriceDimension($priceDimensionTransfer);

        $currencyTransfer = $this->getCurrencyFacade()->fromIsoCode($currencyIsoCode);
        $storeTransfer = $this->getStoreFacade()->getCurrentStore();

        $moneyValueTransfer = $this->createMoneyValueTransfer(
            $grossPrice,
            $netPrice,
            $storeTransfer,
            $currencyTransfer
        );

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        return $priceProductTransfer;
    }

    /**
     * @param int $grossAmount
     * @param int $netAmount
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function createMoneyValueTransfer(
        $grossAmount,
        $netAmount,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): MoneyValueTransfer {
        return (new MoneyValueTransfer())
            ->setNetAmount($netAmount)
            ->setGrossAmount($grossAmount)
            ->setFkStore($storeTransfer->getIdStore())
            ->setFkCurrency($currencyTransfer->getIdCurrency())
            ->setCurrency($currencyTransfer);
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
}
