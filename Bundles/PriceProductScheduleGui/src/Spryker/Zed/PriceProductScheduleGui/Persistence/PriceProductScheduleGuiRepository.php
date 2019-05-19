<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Persistence\PriceProductScheduleGuiPersistenceFactory getFactory()
 */
class PriceProductScheduleGuiRepository extends AbstractRepository implements PriceProductScheduleGuiRepositoryInterface
{
    /**
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function getPriceProductScheduleQuery(): ModelCriteria
    {
        return $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->joinWithCurrency()
            ->joinWithStore()
            ->joinWithPriceType()
            ->leftJoinWithProduct()
            ->leftJoinWithProductAbstract();
    }
}
