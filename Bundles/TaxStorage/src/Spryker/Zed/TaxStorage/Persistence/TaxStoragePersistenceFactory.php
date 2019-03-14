<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

use Orm\Zed\Tax\Persistence\SpyTaxSetTaxQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 */
class TaxStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetTaxQuery
     */
    public function createProductLabelQuery(): SpyTaxSetTaxQuery
    {
        return SpyTaxSetTaxQuery::create();
    }
}
