<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OrderCustomReference\Setter;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Client\OrderCustomReference\Dependency\Client\OrderCustomReferenceToPersistentCartClientInterface;

class OrderCustomReferenceSetter implements OrderCustomReferenceSetterInterface
{
    /**
     * @var \Spryker\Client\OrderCustomReference\Dependency\Client\OrderCustomReferenceToPersistentCartClientInterface
     */
    protected $persistentCartClient;

    /**
     * @param \Spryker\Client\OrderCustomReference\Dependency\Client\OrderCustomReferenceToPersistentCartClientInterface $persistentCartClient
     */
    public function __construct(OrderCustomReferenceToPersistentCartClientInterface $persistentCartClient)
    {
        $this->persistentCartClient = $persistentCartClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setOrderCustomReference(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->requireIdQuote()
            ->requireCustomer();

        $quoteUpdateRequestTransfer = $this->createQuoteUpdateRequestTransfer($quoteTransfer);

        return $this->persistentCartClient->updateQuote($quoteUpdateRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteUpdateRequestTransfer
     */
    protected function createQuoteUpdateRequestTransfer(QuoteTransfer $quoteTransfer): QuoteUpdateRequestTransfer
    {
        $quoteUpdateRequestAttributesTransfer = (new QuoteUpdateRequestAttributesTransfer())
            ->setOrderCustomReference($quoteTransfer->getOrderReference());

        return (new QuoteUpdateRequestTransfer())
            ->setIdQuote($quoteTransfer->getIdQuote())
            ->setCustomer($quoteTransfer->getCustomer())
            ->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributesTransfer);
    }
}
