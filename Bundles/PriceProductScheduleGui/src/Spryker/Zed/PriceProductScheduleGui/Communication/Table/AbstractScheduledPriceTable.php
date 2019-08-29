<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Table;

use DateTime;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface;

abstract class AbstractScheduledPriceTable extends AbstractTable
{
    protected const COL_CURRENCY = 'fk_currency';
    protected const COL_STORE = 'fk_store';
    protected const COL_NET_PRICE = 'net_price';
    protected const COL_GROSS_PRICE = 'gross_price';
    protected const COL_ACTIVE_FROM = 'active_from';
    protected const COL_ACTIVE_TO = 'active_to';
    protected const COL_ACTIONS = 'actions';
    protected const PRICE_NUMERIC_PATTERN = '/[^0-9]+/';

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface
     */
    protected $rowFormatter;

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface $rowFormatter
     */
    public function __construct(
        RowFormatterInterface $rowFormatter
    ) {
        $this->rowFormatter = $rowFormatter;
    }

    /**
     * @return array
     */
    public function getSearchTerm(): array
    {
        $searchTerm = $this->request->query->get('search');

        if (!$this->isSearchTermValid($searchTerm)) {
            return $this->getDefaultSearchTerm();
        }

        $searchTerm[static::PARAMETER_VALUE] = $this->normalizeMoneyValue($searchTerm[static::PARAMETER_VALUE]);

        return $searchTerm;
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
            static::COL_ACTIVE_FROM => 'Start from (included)',
            static::COL_ACTIVE_TO => 'Finish at (included)',
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
            static::COL_ACTIVE_FROM => $this->formatDateTime($priceProductScheduleEntity->getActiveFrom(), $priceProductScheduleEntity->getFkStore()),
            static::COL_ACTIVE_TO => $this->formatDateTime($priceProductScheduleEntity->getActiveTo(), $priceProductScheduleEntity->getFkStore()),
        ];
    }

    /**
     * @param \DateTime $dateTime
     * @param int $fkStore
     *
     * @return string
     */
    protected function formatDateTime(DateTime $dateTime, int $fkStore): string
    {
        return $this->rowFormatter->formatDateTime($dateTime, $fkStore);
    }

    /**
     * @param int|null $amount
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return string|null
     */
    protected function formatMoney(?int $amount, SpyPriceProductSchedule $priceProductScheduleEntity): ?string
    {
        if ($amount === null) {
            return null;
        }

        return $this->rowFormatter->formatMoney($amount, $priceProductScheduleEntity);
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

    /**
     * @param mixed $searchTerm
     *
     * @return bool
     */
    protected function isSearchTermValid($searchTerm): bool
    {
        return is_array($searchTerm)
            && array_key_exists(static::PARAMETER_VALUE, $searchTerm)
            && is_scalar($searchTerm[static::PARAMETER_VALUE]);
    }

    /**
     * @return array
     */
    protected function getDefaultSearchTerm(): array
    {
        return [
            static::PARAMETER_VALUE => '',
        ];
    }

    /**
     * @param string $moneyValue
     *
     * @return string
     */
    protected function normalizeMoneyValue(string $moneyValue): string
    {
        $moneyValue = str_replace('.', '', $moneyValue);

        return str_replace(',', '', $moneyValue);
    }
}
