<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductTaxSetsRestApi\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductTaxSetsRestApi\Persistence\ProductTaxSetsRestApiPersistenceFactory getFactory()
 */
class ProductTaxSetsRestApiEntityManager extends AbstractEntityManager implements ProductTaxSetsRestApiEntityManagerInterface
{
    protected const BATCH_SIZE = 200;

    /**
     * @return void
     */
    public function updateTaxSetsWithoutUuid(): void
    {
        $taxSetsQuery = $this->getFactory()->getTaxSetPropelQuery();

        do {
            $taxSetEntities = $taxSetsQuery
                ->filterByUuid(null, Criteria::ISNULL)
                ->limit(static::BATCH_SIZE)
                ->find();

            foreach ($taxSetEntities as $taxSetEntity) {
                $taxSetEntity->save();
            }
        } while ($taxSetEntities);
    }
}
