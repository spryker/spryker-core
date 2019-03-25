<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\PriceProductSchedule\Helper;

use Codeception\Module;
use DateTime;
use Generated\Shared\DataBuilder\PriceProductScheduleBuilder;
use Generated\Shared\DataBuilder\PriceProductScheduleListBuilder;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class PriceProductScheduleDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    public const EUR_ISO_CODE = 'EUR';
    public const CHF_ISO_CODE = 'CHF';

    public const NET_PRICE = 10;
    public const GROSS_PRICE = 9;

    public const DEFAULT_PRICE_TYPE_ID = 1;
    public const PRICE_TYPE_ID = 2;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param array $priceProductScheduleOverride
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     * @param int|null $netPrice
     * @param int|null $grossPrice
     * @param string|null $currencyIsoCode
     * @param int|null $idPriceType
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function havePriceProductSchedule(
        ProductConcreteTransfer $productConcreteTransfer,
        array $priceProductScheduleOverride = [],
        StoreTransfer $storeTransfer = null,
        int $netPrice = null,
        int $grossPrice = null,
        string $currencyIsoCode = null,
        int $idPriceType = null
    ): PriceProductScheduleTransfer {
        $priceProductTransfer = new PriceProductTransfer();
        $priceProductScheduleData = [
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $this->havePriceProductScheduleList(),
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime())->modify('-4 days'),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime())->modify('+3 days'),
            PriceProductScheduleTransfer::PRICE_PRODUCT => $priceProductTransfer,
        ];

        if (isset($priceProductScheduleOverride[PriceProductScheduleTransfer::ACTIVE_FROM])) {
            $priceProductScheduleData[PriceProductScheduleTransfer::ACTIVE_FROM] = $priceProductScheduleOverride[PriceProductScheduleTransfer::ACTIVE_FROM];
        }

        if (isset($priceProductScheduleOverride[PriceProductScheduleTransfer::ACTIVE_TO])) {
            $priceProductScheduleData[PriceProductScheduleTransfer::ACTIVE_TO] = $priceProductScheduleOverride[PriceProductScheduleTransfer::ACTIVE_TO];
        }

        if (isset($priceProductScheduleOverride[PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST])) {
            $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST] = $priceProductScheduleOverride[PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST];
        }

        $priceProductScheduleTransfer = (new PriceProductScheduleBuilder($priceProductScheduleData))
            ->build();

        if ($storeTransfer === null) {
            $storeTransfer = $this->getStoreFacade()->getCurrentStore();
        }

        if ($idPriceType === null) {
            $idPriceType = self::DEFAULT_PRICE_TYPE_ID;
        }

        if ($currencyIsoCode === null) {
            $currencyIsoCode = self::EUR_ISO_CODE;
        }

        if ($netPrice === null) {
            $netPrice = self::NET_PRICE;
        }

        if ($grossPrice === null) {
            $grossPrice = self::GROSS_PRICE;
        }

        $currencyTransfer = $this->getCurrencyFacade()->fromIsoCode($currencyIsoCode);

        $spyPriceProductScheduleEntity = new SpyPriceProductSchedule();
        $spyPriceProductScheduleEntity->fromArray($priceProductScheduleTransfer->modifiedToArray());
        $spyPriceProductScheduleEntity->setFkStore($storeTransfer->getIdStore());
        $spyPriceProductScheduleEntity->setFkCurrency($currencyTransfer->getIdCurrency());
        $spyPriceProductScheduleEntity->setFkPriceType($idPriceType);
        $spyPriceProductScheduleEntity->setGrossPrice($grossPrice);
        $spyPriceProductScheduleEntity->setNetPrice($netPrice);
        $spyPriceProductScheduleEntity->setFkProduct($productConcreteTransfer->getIdProductConcrete());
        $spyPriceProductScheduleEntity->setFkProductAbstract($productConcreteTransfer->getFkProductAbstract());
        $spyPriceProductScheduleEntity->setFkPriceProductScheduleList($priceProductScheduleTransfer->getPriceProductScheduleList()->getIdPriceProductScheduleList());

        $spyPriceProductScheduleEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($spyPriceProductScheduleEntity) {
            $this->cleanupPriceProductSchedule($spyPriceProductScheduleEntity->getIdPriceProductSchedule());
        });

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
            $this->cleanupPriceProductSchedule($spyPriceProductScheduleListEntity->getIdPriceProductScheduleList());
        });

        return $priceProductScheduleListTransfer;
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
