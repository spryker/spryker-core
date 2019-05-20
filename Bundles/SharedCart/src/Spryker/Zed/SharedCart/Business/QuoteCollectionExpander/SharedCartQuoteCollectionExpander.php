<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\QuoteCollectionExpander;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\SharedQuoteCriteriaFilterTransfer;
use Spryker\Zed\SharedCart\Business\Model\QuoteReaderInterface;
use Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToStoreFacadeInterface;

class SharedCartQuoteCollectionExpander implements SharedCartQuoteCollectionExpanderInterface
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
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function expandQuoteCollectionWithCustomerSharedQuoteCollection(
        CustomerTransfer $customerTransfer,
        QuoteCollectionTransfer $quoteCollectionTransfer
    ): QuoteCollectionTransfer {
        $sharedQuoteCriteriaFilterTransfer = (new SharedQuoteCriteriaFilterTransfer())
            ->setIdCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser())
            ->setIdStore($this->storeFacade->getCurrentStore()->getIdStore());

        $sharedQuoteCollectionTransfer = $this->quoteReader->findCustomerSharedQuoteCollectionBySharedQuoteCriteriaFilter($sharedQuoteCriteriaFilterTransfer);
        foreach ($sharedQuoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            $quoteCollectionTransfer->addQuote($quoteTransfer);
        }

        return $quoteCollectionTransfer;
    }
}
