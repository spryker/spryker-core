<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use Propel\Runtime\Collection\ObjectCollection;

interface ProductOptionValuePriceHydratorInterface
{
    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice[]|\Propel\Runtime\Collection\ObjectCollection $priceCollection
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    public function getMoneyValueCollection(ObjectCollection $priceCollection);
}
