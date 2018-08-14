<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CartsRestApi\Persistence\CartsRestApiPersistenceFactory getFactory()
 */
class CartsRestApiRepository extends AbstractRepository implements CartsRestApiRepositoryInterface
{
    protected const BATCH_SIZE = 200;

    /**
     * @return array
     */
    public function getQuotesWithoutUuid(): array
    {
        $quoteQuery = $this->getFactory()->getQuoteQuery();

        return $quoteQuery
            ->filterByUuid(null, Criteria::ISNULL)
            ->limit(static::BATCH_SIZE)
            ->find()
            ->getData();
    }
}
