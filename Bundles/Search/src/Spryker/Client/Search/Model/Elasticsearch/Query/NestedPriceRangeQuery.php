<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Query;

use Spryker\Shared\Library\Currency\CurrencyManager;

class NestedPriceRangeQuery extends NestedRangeQuery
{

    /**
     * @return array
     */
    protected function getMinMaxValue()
    {
        $currencyManager = CurrencyManager::getInstance();

        list($minValue, $maxValue) = parent::getMinMaxValue();

        $minValue = $currencyManager->convertDecimalToCent($minValue);
        $maxValue = $currencyManager->convertDecimalToCent($maxValue);

        return [$minValue, $maxValue];
    }

}
