<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxSetsRestApi\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\TaxSetsRestApi\Persistence\TaxSetsRestApiPersistenceFactory getFactory()
 */
class TaxSetsRestApiEntityManager extends AbstractEntityManager implements TaxSetsRestApiEntityManagerInterface
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
