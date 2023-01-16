<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Persistence\Propel;

use Orm\Zed\Tax\Persistence\Base\SpyTaxSet as BaseSpyTaxSet;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTaxTableMap;

/**
 * Skeleton subclass for representing a row from the 'spy_tax_set' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpyTaxSet extends BaseSpyTaxSet
{
    /**
     * @return void
     */
    public function initSpyTaxRates()
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $collectionClassName */
        $collectionClassName = SpyTaxSetTaxTableMap::getTableMap()->getCollectionClassName();

        $this->collSpyTaxRates = new $collectionClassName();
        $this->collSpyTaxRates->setModel('\Orm\Zed\Tax\Persistence\SpyTaxRate');
    }
}
