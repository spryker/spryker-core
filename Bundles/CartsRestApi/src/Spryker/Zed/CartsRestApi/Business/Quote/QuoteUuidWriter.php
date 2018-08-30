<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

use Spryker\Zed\CartsRestApi\Persistence\CartsRestApiEntityManagerInterface;

class QuoteUuidWriter implements QuoteUuidWriterInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Persistence\CartsRestApiEntityManagerInterface
     */
    protected $quoteEntityManager;

    /**
     * @param \Spryker\Zed\CartsRestApi\Persistence\CartsRestApiEntityManagerInterface $quoteEntityManager
     */
    public function __construct(CartsRestApiEntityManagerInterface $quoteEntityManager)
    {
        $this->quoteEntityManager = $quoteEntityManager;
    }

    /**
     * @return void
     */
    public function updateQuotesUuid(): void
    {
        $this->quoteEntityManager->setEmptyQuoteUuids();
    }
}
