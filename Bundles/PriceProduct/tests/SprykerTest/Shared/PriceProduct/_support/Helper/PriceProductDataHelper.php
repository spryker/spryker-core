<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\PriceProduct\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\PriceProductBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
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

    protected const EUR_ISO_CODE = 'EUR';
    protected const USD_ISO_CODE = 'USD';
    protected const NET_PRICE = 10;
    protected const GROSS_PRICE = 9;

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
            static::EUR_ISO_CODE
        );

        $priceProductTransfer = $priceProductFacade->createPriceForProduct($priceProductTransfer);

        $this->debug(sprintf(
            'Inserted Price Product Concrete: %d',
            $priceProductTransfer->getIdPriceProduct()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($priceProductTransfer) {
            $this->cleanupPriceProduct($priceProductTransfer->getIdPriceProduct());
        });

        return $priceProductTransfer;
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
        $config = $this->getSharedPriceProductConfig();

        $priceDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType($config->getPriceDimensionDefault());

        $defaultPriceTypeName = $this->getPriceProductFacade()->getDefaultPriceTypeName();

        $priceTypeId = $this->getPriceTypeId($defaultPriceTypeName);

        $priceTypeTransfer = (new PriceTypeTransfer())
            ->setName($defaultPriceTypeName)
            ->setIdPriceType($priceTypeId);

        $priceProductDefaultData = [
            PriceProductTransfer::PRICE_TYPE_NAME => $defaultPriceTypeName,
            PriceProductTransfer::PRICE_TYPE => $priceTypeTransfer,
            PriceProductTransfer::PRICE_DIMENSION => $priceDimensionTransfer,
            PriceProductTransfer::FK_PRICE_TYPE => $priceTypeId,
        ];

        $priceProductTransfer = (new PriceProductBuilder($priceProductDefaultData))
            ->seed($priceProductOverride)
            ->build();

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
}
