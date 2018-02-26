<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Persistence;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Quote\Persistence\QuotePersistenceFactory getFactory()
 */
class QuoteRepository extends AbstractRepository implements QuoteRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $customerReference
     *
     * @return null|\Generated\Shared\Transfer\QuoteTransfer
     */
    public function findQuoteByCustomer($customerReference): ?QuoteTransfer
    {
        $quoteQuery = $this->getFactory()->createQuoteQuery()
            ->joinWithSpyStore()
            ->filterByCustomerReference($customerReference);

        $quoteEntityTransfer = $this->buildQueryFromCriteria($quoteQuery)->findOne();
        if (!$quoteEntityTransfer) {
            return null;
        }

        return $this->getFactory()->createQuoteMapper()->mapEntityTransferToTransfer($quoteEntityTransfer);
    }
}
