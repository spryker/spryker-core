<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Business\Provider;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToPersistentCartFacadeInterface;

class CartReorderProvider implements CartReorderProviderInterface
{
    /**
     * @param \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToPersistentCartFacadeInterface $persistentCartFacade
     */
    public function __construct(protected MultiCartToPersistentCartFacadeInterface $persistentCartFacade)
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

        return $this->createCustomerQuote($customerTransfer);
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

        return $this->persistentCartFacade->createQuote($quoteTransfer)->getQuoteTransferOrFail();
    }
}
