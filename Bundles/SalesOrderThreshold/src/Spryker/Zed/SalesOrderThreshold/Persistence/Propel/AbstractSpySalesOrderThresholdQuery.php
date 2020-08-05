<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Persistence\Propel;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\SalesOrderThreshold\Persistence\Base\SpySalesOrderThresholdQuery as BaseSpySalesOrderThresholdQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'spy_sales_order_threshold' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpySalesOrderThresholdQuery extends BaseSpySalesOrderThresholdQuery
{
    /**
     * @module Store
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return static|$this
     */
    public function filterByStoreTransfer(StoreTransfer $storeTransfer)
    {
        if ($storeTransfer->getIdStore() !== null) {
            return $this->filterByFkStore($storeTransfer->getIdStore());
        }

        $this->useStoreQuery()
                ->filterByName($storeTransfer->getName())
            ->endUse();

        return $this;
    }

    /**
     * @module Currency
     *
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return static|$this
     */
    public function filterByCurrencyTransfer(CurrencyTransfer $currencyTransfer)
    {
        if ($currencyTransfer->getIdCurrency() !== null) {
            return $this->filterByFkCurrency($currencyTransfer->getIdCurrency());
        }

        $this->useCurrencyQuery()
                ->filterByCode($currencyTransfer->getCode())
            ->endUse();

        return $this;
    }
}
