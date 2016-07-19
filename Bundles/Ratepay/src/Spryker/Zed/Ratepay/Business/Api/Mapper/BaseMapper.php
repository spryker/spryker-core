<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Spryker\Shared\Library\Currency\CurrencyManager;

abstract class BaseMapper implements MapperInterface
{

    /**
     * @param int $amount
     *
     * @return float
     */
    protected function centsToDecimal($amount)
    {
        return CurrencyManager::getInstance()->convertCentToDecimal($amount);
    }

}
