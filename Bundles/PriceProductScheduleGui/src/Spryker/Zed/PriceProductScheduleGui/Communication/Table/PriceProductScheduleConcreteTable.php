<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Table;

use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PriceProductScheduleGui\Communication\Controller\IndexController;

class PriceProductScheduleConcreteTable extends AbstractScheduledPriceTable
{
    protected const TABLE_IDENTIFIER = 'price-product-schedule-concrete:';

    /**
     * @var int
     */
    protected $fkProduct;

    /**
     * @var int
     */
    protected $fkPriceType;

    /**
     * @param int $fkProduct
     * @param int $fkPriceType
     */
    public function __construct(int $fkProduct, int $fkPriceType)
    {
        $this->fkProduct = $fkProduct;
        $this->fkPriceType = $fkPriceType;
        $this->baseUrl = '/';
        $this->defaultUrl = Url::generate('price-product-schedule-gui/index/concrete-table', [
            IndexController::REQUEST_KEY_ID_PRODUCT_CONCRETE => $fkProduct,
            IndexController::REQUEST_KEY_ID_PRICE_TYPE => $fkPriceType,
        ])->build();
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = parent::configure($config);
        $this->setTableIdentifier(static::TABLE_IDENTIFIER . $this->fkProduct . ':' . $this->fkPriceType);

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
            ->filterByFkProduct($this->fkProduct)
            ->filterByFkPriceType($this->fkPriceType);
    }
}
