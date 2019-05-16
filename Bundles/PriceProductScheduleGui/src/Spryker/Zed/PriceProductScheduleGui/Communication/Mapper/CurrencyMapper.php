<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Mapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Orm\Zed\Currency\Persistence\SpyCurrency;

class CurrencyMapper implements CurrencyMapperInterface
{
    /**
     * @param \Orm\Zed\Currency\Persistence\SpyCurrency $currencyEntity
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function mapCurrencyEntityToTransfer(SpyCurrency $currencyEntity): CurrencyTransfer
    {
        return (new CurrencyTransfer())->fromArray($currencyEntity->toArray(), true);
    }
}
