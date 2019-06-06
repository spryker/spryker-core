<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\QuoteCollectionExpander;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SharedQuoteCriteriaFilterTransfer;
use Spryker\Zed\SharedCart\Business\Model\QuoteReaderInterface;
use Spryker\Zed\SharedCart\Business\QuoteShareDetails\QuoteShareDetailsReaderInterface;
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
     * @var \Spryker\Zed\SharedCart\Business\QuoteShareDetails\QuoteShareDetailsReaderInterface
     */
    protected $quoteShareDetailsReader;

    /**
     * @param \Spryker\Zed\SharedCart\Business\Model\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\SharedCart\Business\QuoteShareDetails\QuoteShareDetailsReaderInterface $quoteShareDetailsReader
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        SharedCartToStoreFacadeInterface $storeFacade,
        QuoteShareDetailsReaderInterface $quoteShareDetailsReader
    ) {
        $this->quoteReader = $quoteReader;
        $this->storeFacade = $storeFacade;
        $this->quoteShareDetailsReader = $quoteShareDetailsReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function expandQuoteCollectionWithCustomerSharedQuoteCollection(
        QuoteCollectionTransfer $quoteCollectionTransfer,
        QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
    ): QuoteCollectionTransfer {
        if (!$quoteCriteriaFilterTransfer->getIdCompanyUser() || !$quoteCriteriaFilterTransfer->getIdStore()) {
            return $quoteCollectionTransfer;
        }

        $sharedQuoteCriteriaFilterTransfer = (new SharedQuoteCriteriaFilterTransfer())
            ->fromArray($quoteCriteriaFilterTransfer->toArray(), true);

        $sharedQuoteCollectionTransfer = $this->quoteReader
            ->findSharedQuoteCollectionBySharedQuoteCriteriaFilter($sharedQuoteCriteriaFilterTransfer);

        foreach ($sharedQuoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            $quoteTransfer = $this->expandQuoteWithCustomerQuotePermissionGroup($quoteTransfer, $quoteCriteriaFilterTransfer);

            $quoteCollectionTransfer->addQuote($quoteTransfer);
        }

        return $quoteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function expandQuoteWithCustomerQuotePermissionGroup(
        QuoteTransfer $quoteTransfer,
        QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
    ): QuoteTransfer {
        $shareDetailCollectionTransfer = $this->quoteShareDetailsReader
            ->getShareDetailsByIdQuote($quoteTransfer);

        foreach ($shareDetailCollectionTransfer->getShareDetails() as $shareDetailTransfer) {
            if ($quoteCriteriaFilterTransfer->getIdCompanyUser() === $shareDetailTransfer->getIdCompanyUser()) {
                return $quoteTransfer->setQuotePermissionGroup($shareDetailTransfer->getQuotePermissionGroup());
            }
        }

        return $quoteTransfer;
    }
}
