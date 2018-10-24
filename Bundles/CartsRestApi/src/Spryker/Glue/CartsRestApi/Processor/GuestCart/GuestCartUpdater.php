<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\GuestCart;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface;

class GuestCartUpdater implements GuestCartUpdaterInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface
     */
    protected $persistentCartClient;

    /**
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface $quoteClient
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface $persistentCartClient
     */
    public function __construct(
        CartsRestApiToQuoteClientInterface $quoteClient,
        CartsRestApiToPersistentCartClientInterface $persistentCartClient
    ) {
        $this->quoteClient = $quoteClient;
        $this->persistentCartClient = $persistentCartClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function updateGuestCartCustomerReferenceOnRegistration(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $quoteTransfer = $this->quoteClient->getQuote();
        if (!$quoteTransfer->getIdQuote()) {
            return $customerTransfer;
        }

        $quoteUpdateRequestAttributesTransfer = (new QuoteUpdateRequestAttributesTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference());
        $quoteUpdateRequestTransfer = (new QuoteUpdateRequestTransfer())
            ->setIdQuote($quoteTransfer->getIdQuote())
            ->setCustomer($customerTransfer)
            ->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributesTransfer);
        $this->persistentCartClient->updateQuote($quoteUpdateRequestTransfer);

        return $customerTransfer;
    }
}
