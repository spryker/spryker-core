<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Table;

use DateTime;
use DateTimeZone;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToMoneyFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface;

abstract class AbstractScheduledPriceTable extends AbstractTable
{
    protected const COL_CURRENCY = 'fk_currency';
    protected const COL_STORE = 'fk_store';
    protected const COL_NET_PRICE = 'net_price';
    protected const COL_GROSS_PRICE = 'gross_price';
    protected const COL_ACTIVE_FROM = 'active_from';
    protected const COL_ACTIVE_TO = 'active_to';
    protected const COL_ACTIONS = 'actions';
    protected const DATE_FORMAT = 'Y-m-d e H:i:s';

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(
        PriceProductScheduleGuiToStoreFacadeInterface $storeFacade,
        PriceProductScheduleGuiToMoneyFacadeInterface $moneyFacade
    ) {
        $this->storeFacade = $storeFacade;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_CURRENCY => 'Currency',
            static::COL_STORE => 'Store',
            static::COL_NET_PRICE => 'Net price',
            static::COL_GROSS_PRICE => 'Gross price',
            static::COL_ACTIVE_FROM => 'Active from',
            static::COL_ACTIVE_TO => 'Active to',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setSearchable([
            static::COL_NET_PRICE,
            static::COL_GROSS_PRICE,
        ]);

        $config->setSortable([
            static::COL_CURRENCY,
            static::COL_STORE,
            static::COL_NET_PRICE,
            static::COL_GROSS_PRICE,
            static::COL_ACTIVE_FROM,
            static::COL_ACTIVE_TO,
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $query = $this->prepareQuery();
        $queryResults = $this->runQuery($query, $config, true);

        $priceProductScheduleCollection = [];

        foreach ($queryResults as $priceProductScheduleEntity) {
            $priceProductScheduleCollection[] = $this->generateItem($priceProductScheduleEntity);
        }

        return $priceProductScheduleCollection;
    }

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    abstract protected function prepareQuery(): SpyPriceProductScheduleQuery;

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return array
     */
    protected function generateItem(SpyPriceProductSchedule $priceProductScheduleEntity): array
    {
        return [
            static::COL_NET_PRICE => $this->formatMoney($priceProductScheduleEntity->getNetPrice(), $priceProductScheduleEntity),
            static::COL_GROSS_PRICE => $this->formatMoney($priceProductScheduleEntity->getGrossPrice(), $priceProductScheduleEntity),
            static::COL_STORE => $priceProductScheduleEntity->getStore()->getName(),
            static::COL_CURRENCY => $priceProductScheduleEntity->getCurrency()->getCode(),
            static::COL_ACTIVE_FROM => $this->prepareDateTime($priceProductScheduleEntity->getActiveFrom(), $priceProductScheduleEntity)->format(static::DATE_FORMAT),
            static::COL_ACTIVE_TO => $this->prepareDateTime($priceProductScheduleEntity->getActiveTo(), $priceProductScheduleEntity)->format(static::DATE_FORMAT),
            static::COL_ACTIONS => implode(' ', $this->createActionColumn($priceProductScheduleEntity)),
        ];
    }

    /**
     * @param int $amount
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return string
     */
    protected function formatMoney(int $amount, SpyPriceProductSchedule $priceProductScheduleEntity): string
    {
        $currencyTransfer = (new CurrencyTransfer())->fromArray($priceProductScheduleEntity->getCurrency()->toArray(), true);
        $moneyTransfer = new MoneyTransfer();
        $moneyTransfer->setAmount($amount);
        $moneyTransfer->setCurrency($currencyTransfer);

        return $this->moneyFacade->formatWithSymbol($moneyTransfer);
    }

    /**
     * @param \DateTime $dateTime
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return \DateTime
     */
    protected function prepareDateTime(DateTime $dateTime, SpyPriceProductSchedule $priceProductScheduleEntity): DateTime
    {
        $storeTransfer = $this->storeFacade->getStoreById($priceProductScheduleEntity->getFkStore());
        $timeZone = new DateTimeZone($storeTransfer->getTimezone());

        return $dateTime->setTimezone($timeZone);
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $item
     *
     * @return string[]
     */
    protected function createActionColumn(SpyPriceProductSchedule $item): array
    {
        return [
            $this->generatePriceProductScheduleEditButton($item),
            $this->generatePriceProductScheduleRemoveButton($item),
        ];
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $item
     *
     * @return string
     */
    protected function generatePriceProductScheduleEditButton(SpyPriceProductSchedule $item): string
    {
        return $this->generateEditButton(
            Url::generate('/price-product-schedule-gui/edit', [
                'id-price-product-schedule' => $item->getIdPriceProductSchedule(),
            ]),
            'Edit'
        );
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $item
     *
     * @return string
     */
    protected function generatePriceProductScheduleRemoveButton(SpyPriceProductSchedule $item): string
    {
        return $this->generateRemoveButton(
            Url::generate('/price-product-schedule-gui/delete', [
                'id-price-product-schedule' => $item->getIdPriceProductSchedule(),
            ]),
            'Delete'
        );
    }
}
