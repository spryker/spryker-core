<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\PriceProductSchedule\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\DataBuilder\PriceProductBuilder;
use Generated\Shared\DataBuilder\PriceProductScheduleBuilder;
use Generated\Shared\DataBuilder\PriceProductScheduleListBuilder;
use Generated\Shared\DataBuilder\PriceTypeBuilder;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleListQuery;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;
use Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

/**
 * @method \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface getFacade()
 */
class PriceProductScheduleDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    public const EUR_ISO_CODE = 'EUR';

    public const NET_PRICE = 100;
    public const GROSS_PRICE = 80;

    /**
     * @param array $priceProductScheduleOverrideData
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function havePriceProductSchedule(array $priceProductScheduleOverrideData = []): PriceProductScheduleTransfer
    {
        $priceTypeOverrideData = $priceProductScheduleOverrideData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::PRICE_TYPE] ?? [];
        $priceTypeTransfer = (new PriceTypeBuilder($priceTypeOverrideData))
            ->build();
        unset($priceProductScheduleOverrideData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::PRICE_TYPE]);

        $currencyTransfer = $this->getCurrencyFacade()->fromIsoCode(static::EUR_ISO_CODE);

        $moneyValueData = [
            MoneyValueTransfer::FK_STORE => $this->getStoreFacade()->getCurrentStore()->getIdStore(),
            MoneyValueTransfer::FK_CURRENCY => $currencyTransfer->getIdCurrency(),
            MoneyValueTransfer::CURRENCY => $currencyTransfer,
            MoneyValueTransfer::NET_AMOUNT => static::NET_PRICE,
            MoneyValueTransfer::GROSS_AMOUNT => static::GROSS_PRICE,
        ];

        $moneyValueOverrideData = $priceProductScheduleOverrideData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::MONEY_VALUE] ?? [];
        $moneyValueTransfer = (new MoneyValueBuilder(array_merge($moneyValueData, $moneyValueOverrideData)))
            ->build();
        unset($priceProductScheduleOverrideData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::MONEY_VALUE]);

        $priceProductData = [
            PriceProductTransfer::PRICE_TYPE => $priceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => $moneyValueTransfer,
        ];

        $priceProductOverrideData = $priceProductScheduleOverrideData[PriceProductScheduleTransfer::PRICE_PRODUCT] ?? [];
        $priceProductTransfer = (new PriceProductBuilder(array_merge($priceProductData, $priceProductOverrideData)))
            ->build();
        unset($priceProductScheduleOverrideData[PriceProductScheduleTransfer::PRICE_PRODUCT]);

        $priceProductScheduleData = [
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $this->havePriceProductScheduleList(),
            PriceProductScheduleTransfer::PRICE_PRODUCT => $priceProductTransfer,
        ];

        $priceProductScheduleTransfer = (new PriceProductScheduleBuilder(array_merge($priceProductScheduleData, $priceProductScheduleOverrideData)))
            ->build();

        $spyPriceProductScheduleEntity = new SpyPriceProductSchedule();
        $spyPriceProductScheduleEntity->fromArray($priceProductScheduleTransfer->modifiedToArray());
        $spyPriceProductScheduleEntity->setFkStore($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getFkStore());
        $spyPriceProductScheduleEntity->setFkCurrency($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getCurrency()->getIdCurrency());
        $spyPriceProductScheduleEntity->setFkPriceType($priceProductScheduleTransfer->getPriceProduct()->getPriceType()->getIdPriceType());
        $spyPriceProductScheduleEntity->setGrossPrice($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getGrossAmount());
        $spyPriceProductScheduleEntity->setNetPrice($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getNetAmount());
        $spyPriceProductScheduleEntity->setFkProduct($priceProductScheduleTransfer->getPriceProduct()->getIdProduct());
        $spyPriceProductScheduleEntity->setFkProductAbstract($priceProductScheduleTransfer->getPriceProduct()->getIdProductAbstract());
        $spyPriceProductScheduleEntity->setFkPriceProductScheduleList($priceProductScheduleTransfer->getPriceProductScheduleList()->getIdPriceProductScheduleList());

        $spyPriceProductScheduleEntity->save();

        $priceProductScheduleTransfer->setIdPriceProductSchedule($spyPriceProductScheduleEntity->getIdPriceProductSchedule());

        return $priceProductScheduleTransfer;
    }

    /**
     * @param bool|null $isActive
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer
     */
    public function havePriceProductScheduleList(?bool $isActive = true): PriceProductScheduleListTransfer
    {
        $priceProductScheduleListTransfer = (new PriceProductScheduleListBuilder([
            PriceProductScheduleListTransfer::IS_ACTIVE => $isActive,
        ]))
            ->build();

        $spyPriceProductScheduleListEntity = new SpyPriceProductScheduleList();
        $spyPriceProductScheduleListEntity->fromArray($priceProductScheduleListTransfer->modifiedToArray());
        $spyPriceProductScheduleListEntity->save();

        $priceProductScheduleListTransfer->setIdPriceProductScheduleList($spyPriceProductScheduleListEntity->getIdPriceProductScheduleList());

        return $priceProductScheduleListTransfer;
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
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleListQuery
     */
    protected function getPriceProductScheduleListQuery(): SpyPriceProductScheduleListQuery
    {
        return new SpyPriceProductScheduleListQuery();
    }

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    public function getPriceProductScheduleQuery(): SpyPriceProductScheduleQuery
    {
        return new SpyPriceProductScheduleQuery();
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
