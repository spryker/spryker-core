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
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToStoreFacadeInterface;

class QuoteResponseExpander implements QuoteResponseExpanderInterface
{
    /**
     * @var \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        MultiCartToQuoteFacadeInterface $quoteFacade,
        MultiCartToStoreFacadeInterface $storeFacade
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function expand(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        $customerTransfer = $quoteResponseTransfer->getCustomer();
        $storeTransfer = $this->storeFacade->getCurrentStore();

        $customerQuoteCollectionTransfer = $this->findCustomerQuotesByStore($customerTransfer, $storeTransfer);
        $quoteResponseTransfer->setCustomerQuotes($customerQuoteCollectionTransfer);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function findCustomerQuotesByStore(CustomerTransfer $customerTransfer, StoreTransfer $storeTransfer): QuoteCollectionTransfer
    {
        $filterTransfer = new FilterTransfer();
        $filterTransfer
            ->setOrderBy(QuoteTransfer::NAME)
            ->setOrderDirection('ASC');

        $quoteCriteriaFilterTransfer = new QuoteCriteriaFilterTransfer();
        $quoteCriteriaFilterTransfer
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setIdStore($storeTransfer->getIdStore())
            ->setFilter($filterTransfer);

        $customerQuoteCollectionTransfer = $this->quoteFacade->getQuoteCollection($quoteCriteriaFilterTransfer);
        foreach ($customerQuoteCollectionTransfer->getQuotes() as $customerQuoteTransfer) {
            $customerQuoteTransfer->setCustomer($customerTransfer);
        }

        return $customerQuoteCollectionTransfer;
    }
}
