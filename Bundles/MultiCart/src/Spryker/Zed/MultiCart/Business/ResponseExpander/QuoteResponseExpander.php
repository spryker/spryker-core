<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Business\ResponseExpander;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface;

class QuoteResponseExpander implements QuoteResponseExpanderInterface
{
    /**
     * @var \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * QuoteActivator constructor.
     *
     * @param \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(MultiCartToQuoteFacadeInterface $quoteFacade)
    {
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function expand(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $customerTransfer = $quoteTransfer->requireCustomer()->getCustomer();

        $customerQuoteCollectionTransfer = $this->findCustomerQuotes($customerTransfer);
        $quoteResponseTransfer->setCustomerQuotes($customerQuoteCollectionTransfer);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function findCustomerQuotes(CustomerTransfer $customerTransfer): QuoteCollectionTransfer
    {
        $filterTransfer = new FilterTransfer();
        $filterTransfer
            ->setOrderBy('name')
            ->setOrderDirection('ASC');

        $quoteCriteriaFilterTransfer = new QuoteCriteriaFilterTransfer();
        $quoteCriteriaFilterTransfer
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setFilter($filterTransfer);

        $customerQuoteCollectionTransfer = $this->quoteFacade->getQuoteCollection($quoteCriteriaFilterTransfer);
        foreach ($customerQuoteCollectionTransfer->getQuotes() as $customerQuoteTransfer) {
            $customerQuoteTransfer->setCustomer($customerTransfer);
        }

        return $customerQuoteCollectionTransfer;
    }
}
