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
use Spryker\Zed\PriceProductScheduleGui\Communication\Controller\IndexController;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface;

class PriceProductScheduleConcreteTable extends AbstractScheduledPriceTable
{
    protected const PATTERN_TABLE_IDENTIFIER = 'price-product-schedule-concrete:%s:%s';
    protected const PATTERN_REDIRECT_URL = '/product-management/edit/variant?id-product-abstract=%s&id-product=%s#tab-content-scheduled_prices';

    /**
     * @var \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected $priceProductScheduleQuery;

    /**
     * @var int
     */
    protected $idProduct;

    /**
     * @var int
     */
    protected $idProductAbstract;

    /**
     * @var int
     */
    protected $idPriceType;

    /**
     * @param int $idProduct
     * @param int $idProductAbstract
     * @param int $idPriceType
     * @param \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface $rowFormatter
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery $priceProductScheduleQuery
     */
    public function __construct(
        int $idProduct,
        int $idProductAbstract,
        int $idPriceType,
        RowFormatterInterface $rowFormatter,
        SpyPriceProductScheduleQuery $priceProductScheduleQuery
    ) {
        parent::__construct($rowFormatter);
        $this->idProduct = $idProduct;
        $this->idProductAbstract = $idProductAbstract;
        $this->idPriceType = $idPriceType;
        $this->baseUrl = '/';
        $this->defaultUrl = Url::generate('price-product-schedule-gui/index/concrete-product-table', [
            IndexController::REQUEST_KEY_ID_PRODUCT_CONCRETE => $idProduct,
            IndexController::REQUEST_KEY_ID_PRICE_TYPE => $idPriceType,
            IndexController::REQUEST_KEY_ID_PRODUCT_ABSTRACT => $idProductAbstract,
        ])->build();
        $this->priceProductScheduleQuery = $priceProductScheduleQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = parent::configure($config);
        $this->setTableIdentifier(sprintf(static::PATTERN_TABLE_IDENTIFIER, $this->idProduct, $this->idPriceType));

        return $config;
    }

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected function prepareQuery(): SpyPriceProductScheduleQuery
    {
        return (new SpyPriceProductScheduleQuery())
            ->leftJoinWithCurrency()
            ->leftJoinWithStore()
            ->filterByFkProduct($this->idProduct)
            ->filterByFkPriceType($this->idPriceType);
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
                'id-product-abstract' => $this->idProductAbstract,
                'id-product' => $this->idProduct,
            ]),
            'Delete'
        );
    }
}
