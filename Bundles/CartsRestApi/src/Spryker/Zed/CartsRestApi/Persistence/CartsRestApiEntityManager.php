<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CartsRestApi\Persistence\CartsRestApiPersistenceFactory getFactory()
 */
class CartsRestApiEntityManager extends AbstractEntityManager implements CartsRestApiEntityManagerInterface
{
    /**
     * @var int
     */
    protected const BATCH_SIZE = 200;

    /**
     * @return void
     */
    public function setEmptyQuoteUuids(): void
    {
        $quoteQuery = $this->getFactory()->getQuoteQuery();

        do {
            /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Quote\Persistence\SpyQuote> $quotes */
            $quotes = $quoteQuery
                ->filterByUuid(null, Criteria::ISNULL)
                ->limit(static::BATCH_SIZE)
                ->find();

            foreach ($quotes as $quote) {
                $quote->save();
            }
        } while ($quotes->count());
    }
}
