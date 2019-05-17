<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Business\QuoteCollectionExpander;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\SharedQuoteCriteriaFilterTransfer;
use Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToSharedCartFacadeInterface;
use Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToStoreFacadeInterface;

class SharedCartQuoteCollectionExpander implements SharedCartQuoteCollectionExpanderInterface
{
    /**
     * @var \Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToSharedCartFacadeInterface
     */
    protected $sharedCartFacade;

    /**
     * @var \Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToSharedCartFacadeInterface $sharedCartFacade
     * @param \Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        SharedCartsRestApiToSharedCartFacadeInterface $sharedCartFacade,
        SharedCartsRestApiToStoreFacadeInterface $storeFacade
    ) {
        $this->sharedCartFacade = $sharedCartFacade;
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

        $sharedQuoteCollectionTransfer = $this->sharedCartFacade->getCustomerSharedQuoteCollection($sharedQuoteCriteriaFilterTransfer);
        foreach ($sharedQuoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            $quoteCollectionTransfer->addQuote($quoteTransfer);
        }

        return $quoteCollectionTransfer;
    }
}
