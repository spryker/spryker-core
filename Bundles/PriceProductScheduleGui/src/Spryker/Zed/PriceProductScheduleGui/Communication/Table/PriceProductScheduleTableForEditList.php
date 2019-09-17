<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Table;

use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PriceProductScheduleGui\Communication\Controller\EditScheduleListController;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface;

class PriceProductScheduleTableForEditList extends AbstractPriceProductScheduleTable
{
    protected const COL_ACTIONS = 'actions';

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface $rowFormatter
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery $priceProductScheduleQuery
     * @param int $idPriceProductScheduleList
     */
    public function __construct(
        RowFormatterInterface $rowFormatter,
        SpyPriceProductScheduleQuery $priceProductScheduleQuery,
        int $idPriceProductScheduleList
    ) {
        parent::__construct($rowFormatter, $priceProductScheduleQuery, $idPriceProductScheduleList);

        $this->defaultUrl = Url::generate('price-product-schedule-gui/edit-schedule-list/table', [
            EditScheduleListController::PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST => $idPriceProductScheduleList,
        ])->build();
    }

    /**
     * @return array
     */
    protected function getCustomHeaders(): array
    {
        return [
            static::COL_ACTIONS => 'Actions',
        ];
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setRawColumns(TableConfiguration $config): TableConfiguration
    {
        $config->setRawColumns([
            static::COL_ACTIONS,
        ]);

        return $config;
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return array
     */
    protected function generateCustomItemFields(SpyPriceProductSchedule $priceProductScheduleEntity): array
    {
        return [
            static::COL_ACTIONS => implode(' ', $this->createActionColumn($priceProductScheduleEntity)),
        ];
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
                'id-price-product-schedule-list' => $item->getFkPriceProductScheduleList(),
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
