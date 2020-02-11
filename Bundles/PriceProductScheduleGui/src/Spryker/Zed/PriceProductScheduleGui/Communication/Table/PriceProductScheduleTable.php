<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Table;

use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\PriceProductScheduleGui\Communication\Controller\ViewScheduleListController;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface;

class PriceProductScheduleTable extends AbstractPriceProductScheduleTable
{
    /**
     * @var int
     */
    protected $idPriceProductScheduleList;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface
     */
    protected $rowFormatter;

    /**
     * @var \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected $priceProductScheduleQuery;

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

        $this->defaultUrl = Url::generate('price-product-schedule-gui/view-schedule-list/table', [
            ViewScheduleListController::PARAM_ID_PRICE_PRODUCT_SCHEDULE_LIST => $idPriceProductScheduleList,
        ])->build();
    }
}
