<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander;

use Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleAbstractTable;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleConcreteTable;

interface ViewExpanderTableFactoryInterface
{
    /**
     * @param int $idProductAbstract
     * @param int $idPriceType
     *
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleAbstractTable
     */
    public function createPriceProductScheduleAbstractTable(
        int $idProductAbstract,
        int $idPriceType
    ): PriceProductScheduleAbstractTable;

    /**
     * @param int $idProductConcrete
     * @param int $idPriceType
     *
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleConcreteTable
     */
    public function createPriceProductScheduleConcreteTable(
        int $idProductConcrete,
        int $idPriceType
    ): PriceProductScheduleConcreteTable;
}
