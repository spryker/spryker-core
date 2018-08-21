<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxSetsRestApi\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\TaxSetsRestApi\Persistence\TaxSetsRestApiPersistenceFactory getFactory()
 */
class TaxSetsRestApiRepository extends AbstractRepository implements TaxSetsRestApiRepositoryInterface
{
    protected const BATCH_SIZE = 200;

    /**
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSet[]
     */
    public function getTaxSetEntitiesWithoutUuid(): array
    {
        return $this->getFactory()
            ->getTaxSetPropelQuery()
            ->filterByUuid(null, Criteria::ISNULL)
            ->limit(static::BATCH_SIZE)
            ->find()
            ->getData();
    }
}
