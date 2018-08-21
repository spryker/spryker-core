<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Persistence;

use Orm\Zed\Quote\Persistence\SpyQuote;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CartsRestApi\Persistence\CartsRestApiPersistenceFactory getFactory()
 */
class CartsRestApiEntityManager extends AbstractEntityManager implements CartsRestApiEntityManagerInterface
{
    /**
     * @param \Orm\Zed\Quote\Persistence\SpyQuote $quote
     *
     * @return void
     */
    public function saveQuoteWithoutUuid(SpyQuote $quote): void
    {
        $quote->save();
    }
}
