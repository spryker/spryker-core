<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Provider;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PersistentCart\Business\Model\QuoteWriterInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface;

class CartReorderProvider implements CartReorderProviderInterface
{
    /**
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteWriterInterface $quoteWriter
     */
    public function __construct(protected PersistentCartToQuoteFacadeInterface $quoteFacade, protected QuoteWriterInterface $quoteWriter)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuoteForCartReorder(CartReorderRequestTransfer $cartReorderRequestTransfer): QuoteTransfer
    {
        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($cartReorderRequestTransfer->getCustomerReferenceOrFail());

        $customerQuote = $this->findCustomerQuote($customerTransfer);
        if (!$customerQuote) {
            $customerQuote = $this->createCustomerQuote($customerTransfer);
        }

        return $customerQuote
            ->setCustomer($customerQuote->getCustomer() ?? $customerTransfer)
            ->setItems(new ArrayObject());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function findCustomerQuote(CustomerTransfer $customerTransfer): ?QuoteTransfer
    {
        $quoteCriteriaFilterTransfer = (new QuoteCriteriaFilterTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReferenceOrFail());

        $quoteCollectionTransfer = $this->quoteFacade->getQuoteCollection($quoteCriteriaFilterTransfer);
        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if ($quoteTransfer->getIsDefault()) {
                return $quoteTransfer;
            }
        }

        return $quoteCollectionTransfer->getQuotes()->getIterator()->current();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createCustomerQuote(CustomerTransfer $customerTransfer): QuoteTransfer
    {
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReferenceOrFail())
            ->setCustomer($customerTransfer);

        return $this->quoteWriter->createQuote($quoteTransfer)->getQuoteTransferOrFail();
    }
}
