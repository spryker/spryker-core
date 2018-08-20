<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Persistence;

use Orm\Zed\Quote\Persistence\SpyQuote;

interface CartsRestApiEntityManagerInterface
{
    /**
     * @param \Orm\Zed\Quote\Persistence\SpyQuote $quote
     *
     * @return void
     */
    public function saveQuoteWithoutUuid(SpyQuote $quote): void;
}
