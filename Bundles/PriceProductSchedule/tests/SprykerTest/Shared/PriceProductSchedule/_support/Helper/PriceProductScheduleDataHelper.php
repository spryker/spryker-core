<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\PriceProductSchedule\Helper;

use Codeception\Module;
use DateTime;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\DataBuilder\PriceProductBuilder;
use Generated\Shared\DataBuilder\PriceProductScheduleBuilder;
use Generated\Shared\DataBuilder\PriceProductScheduleListBuilder;
use Generated\Shared\DataBuilder\PriceTypeBuilder;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
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

class PriceProductScheduleDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    public const EUR_ISO_CODE = 'EUR';

    public const NET_PRICE = 100;
    public const GROSS_PRICE = 80;

    public const ABSTRACT_ID_PRODUCT = 1;
    public const CONCRETE_ID_PRODUCT = 1;

    /**
     * @param array $priceProductScheduleOverride
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function havePriceProductSchedule(array $priceProductScheduleOverride = []): PriceProductScheduleTransfer
    {
        $defaultPriceTypeName = $this->getPriceProductFacade()->getDefaultPriceTypeName();

        $priceTypeData = [
            PriceTypeTransfer::NAME => $defaultPriceTypeName,
            PriceTypeTransfer::ID_PRICE_TYPE => $this->getPriceTypeId($defaultPriceTypeName),
        ];

        $priceTypeTransfer = (new PriceTypeBuilder($priceTypeData))
            ->seed($priceProductScheduleOverride[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::PRICE_TYPE] ?? [])
            ->build();
        $currencyTransfer = $this->getCurrencyFacade()->fromIsoCode(static::EUR_ISO_CODE);

        $moneyValueData = [
            MoneyValueTransfer::FK_STORE => $this->getStoreFacade()->getCurrentStore()->getIdStore(),
            MoneyValueTransfer::FK_CURRENCY => $currencyTransfer->getIdCurrency(),
            MoneyValueTransfer::CURRENCY => $currencyTransfer,
            MoneyValueTransfer::NET_AMOUNT => static::NET_PRICE,
            MoneyValueTransfer::GROSS_AMOUNT => static::GROSS_PRICE,
        ];

        $moneyValueTransfer = (new MoneyValueBuilder($moneyValueData))
            ->seed($priceProductScheduleOverride[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::MONEY_VALUE] ?? [])
            ->build();

        $priceProductData = [
            PriceProductTransfer::PRICE_TYPE => $priceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => $moneyValueTransfer,
            PriceProductTransfer::ID_PRODUCT_ABSTRACT => static::ABSTRACT_ID_PRODUCT,
            PriceProductTransfer::ID_PRODUCT => static::CONCRETE_ID_PRODUCT,
        ];

        $priceProductTransfer = (new PriceProductBuilder($priceProductData))
            ->seed($priceProductScheduleOverride[PriceProductScheduleTransfer::PRICE_PRODUCT] ?? [])
            ->build();

        $priceProductScheduleData = [
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $this->havePriceProductScheduleList(),
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime())->modify('-4 days'),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime())->modify('+3 days'),
            PriceProductScheduleTransfer::PRICE_PRODUCT => $priceProductTransfer,
        ];

        $priceProductScheduleTransfer = (new PriceProductScheduleBuilder($priceProductScheduleData))
            ->seed($priceProductScheduleOverride)
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

        $this->getDataCleanupHelper()->_addCleanup(function () use ($spyPriceProductScheduleEntity) {
            $this->cleanupPriceProductSchedule($spyPriceProductScheduleEntity->getIdPriceProductSchedule());
        });

        $priceProductScheduleTransfer->setIdPriceProductSchedule($spyPriceProductScheduleEntity->getIdPriceProductSchedule());

        return $priceProductScheduleTransfer;
    }

    /**
     * @param bool|null $isActive
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer
     */
    public function havePriceProductScheduleList($isActive = true): PriceProductScheduleListTransfer
    {
        $priceProductScheduleListTransfer = (new PriceProductScheduleListBuilder([
            PriceProductScheduleListTransfer::IS_ACTIVE => $isActive,
        ]))
            ->build();

        $spyPriceProductScheduleListEntity = new SpyPriceProductScheduleList();
        $spyPriceProductScheduleListEntity->fromArray($priceProductScheduleListTransfer->modifiedToArray());
        $spyPriceProductScheduleListEntity->save();

        $priceProductScheduleListTransfer->setIdPriceProductScheduleList($spyPriceProductScheduleListEntity->getIdPriceProductScheduleList());
        $this->getDataCleanupHelper()->_addCleanup(function () use ($spyPriceProductScheduleListEntity) {
            $this->cleanupPriceProductScheduleList($spyPriceProductScheduleListEntity->getIdPriceProductScheduleList());
        });

        return $priceProductScheduleListTransfer;
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
     * @param int $idPriceProductScheduleList
     *
     * @return void
     */
    private function cleanupPriceProductScheduleList(int $idPriceProductScheduleList): void
    {
        $this->getPriceProductScheduleListQuery()
            ->findByIdPriceProductScheduleList($idPriceProductScheduleList)
            ->delete();
    }

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleListQuery
     */
    protected function getPriceProductScheduleListQuery(): SpyPriceProductScheduleListQuery
    {
        return new SpyPriceProductScheduleListQuery();
    }

    /**
     * @param int $idPriceProductSchedule
     *
     * @return void
     */
    private function cleanupPriceProductSchedule(int $idPriceProductSchedule): void
    {
        $this->getPriceProductScheduleQuery()
            ->findByIdPriceProductSchedule($idPriceProductSchedule)
            ->delete();
    }

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected function getPriceProductScheduleQuery(): SpyPriceProductScheduleQuery
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
