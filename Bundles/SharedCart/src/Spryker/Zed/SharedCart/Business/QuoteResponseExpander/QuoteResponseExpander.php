<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\QuoteResponseExpander;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Zed\SharedCart\Business\Model\QuoteReaderInterface;

class QuoteResponseExpander implements QuoteResponseExpanderInterface
{
    /**
     * @var \Spryker\Zed\SharedCart\Business\Model\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @param \Spryker\Zed\SharedCart\Business\Model\QuoteReaderInterface $quoteReader
     */
    public function __construct(QuoteReaderInterface $quoteReader)
    {
        $this->quoteReader = $quoteReader;
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

        $sharedQuoteCollectionTransfer = $this->findSharedCustomerQuotes($customerTransfer);
        $quoteResponseTransfer->setSharedCustomerQuotes($sharedQuoteCollectionTransfer);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function findSharedCustomerQuotes(CustomerTransfer $customerTransfer): QuoteCollectionTransfer
    {
        if ($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser()) {
            return $this->quoteReader->findCustomerSharedQuotes($customerTransfer);
        }

        return new QuoteCollectionTransfer();
    }
}
