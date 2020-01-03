<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\QuoteResponseExpander;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\SharedQuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\SharedCart\Business\Model\QuoteReaderInterface;
use Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToStoreFacadeInterface;

class CustomerShareCartQuoteResponseExpander implements QuoteResponseExpanderInterface
{
    /**
     * @var \Spryker\Zed\SharedCart\Business\Model\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @var \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\SharedCart\Business\Model\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        SharedCartToStoreFacadeInterface $storeFacade
    ) {
        $this->quoteReader = $quoteReader;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function expand(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        $customerTransfer = $quoteResponseTransfer->requireCustomer()
            ->getCustomer();
        $storeTransfer = $this->storeFacade->getCurrentStore();

        $sharedQuoteCollectionTransfer = $this->findSharedCustomerQuotesByStore($customerTransfer, $storeTransfer);

        $this->populateSharedQuoteCollectionWithCustomer($sharedQuoteCollectionTransfer, $customerTransfer);
        $quoteResponseTransfer->setSharedCustomerQuotes($sharedQuoteCollectionTransfer);

        if (!$quoteResponseTransfer->getQuoteTransfer()) {
            return $quoteResponseTransfer;
        }

        return $this->replaceCurrentQuoteFromList($quoteResponseTransfer, $sharedQuoteCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function findSharedCustomerQuotesByStore(CustomerTransfer $customerTransfer, StoreTransfer $storeTransfer): QuoteCollectionTransfer
    {
        if ($customerTransfer->getCompanyUserTransfer()) {
            $sharedQuoteCriteriaFilter = (new SharedQuoteCriteriaFilterTransfer())
                ->setIdCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser())
                ->setIdStore($storeTransfer->getIdStore());

            return $this->quoteReader->findSharedQuoteCollectionBySharedQuoteCriteriaFilter($sharedQuoteCriteriaFilter);
        }

        return new QuoteCollectionTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $sharedQuoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function replaceCurrentQuoteFromList(QuoteResponseTransfer $quoteResponseTransfer, QuoteCollectionTransfer $sharedQuoteCollectionTransfer): QuoteResponseTransfer
    {
        $currentQuoteTransfer = $quoteResponseTransfer->requireQuoteTransfer()->getQuoteTransfer();
        foreach ($sharedQuoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if ($quoteTransfer->getIdQuote() === $currentQuoteTransfer->getIdQuote()) {
                $quoteResponseTransfer->setQuoteTransfer(
                    $currentQuoteTransfer->fromArray($quoteTransfer->modifiedToArray(), true)
                );
            }
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $sharedQuoteCollectionTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function populateSharedQuoteCollectionWithCustomer(QuoteCollectionTransfer $sharedQuoteCollectionTransfer, CustomerTransfer $customerTransfer): void
    {
        foreach ($sharedQuoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            $quoteTransfer->setCustomer($customerTransfer);
        }
    }
}
