<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Business\Model;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface;

class QuoteReader implements QuoteReaderInterface
{
    /**
     * @var \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(MultiCartToQuoteFacadeInterface $quoteFacade)
    {
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function findCustomerQuotes(CustomerTransfer $customerTransfer): QuoteCollectionTransfer
    {
        $quoteCriteriaFilterTransfer = new QuoteCriteriaFilterTransfer();
        $quoteCriteriaFilterTransfer->setCustomerReference($customerTransfer->getCustomerReference());
        return $this->quoteFacade->getQuoteCollection($quoteCriteriaFilterTransfer);
    }
}
