<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface;

class SingleQuoteCollectionReader implements SingleQuoteCollectionReaderInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(CartsRestApiToQuoteFacadeInterface $quoteFacade)
    {
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollectionByCriteria(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer
    {
        $quoteCollectionTransfer = new QuoteCollectionTransfer();

        if (!$quoteCriteriaFilterTransfer->getCustomerReference()) {
            return $quoteCollectionTransfer;
        }

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setCustomerReference($quoteCriteriaFilterTransfer->getCustomerReference());

        $quoteResponseTransfer = $this->quoteFacade->findQuoteByCustomer($customerTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteCollectionTransfer;
        }

        return $quoteCollectionTransfer->addQuote($quoteResponseTransfer->getQuoteTransfer());
    }
}
