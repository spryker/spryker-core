<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\Quote;

use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientInterface;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;

class QuoteProcessor implements QuoteProcessorInterface
{
    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    protected $quoteCollectionReaderPlugin;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface
     */
    protected $checkoutDataMapper;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientInterface $cartClient
     * @param \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface $quoteCollectionReaderPlugin
     */
    public function __construct(
        CheckoutRestApiToCartClientInterface $cartClient,
        QuoteCollectionReaderPluginInterface $quoteCollectionReaderPlugin
    ) {
        $this->cartClient = $cartClient;
        $this->quoteCollectionReaderPlugin = $quoteCollectionReaderPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findCustomerQuote(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): ?QuoteTransfer
    {
        $quoteIdentifier = $restCheckoutRequestAttributesTransfer->getQuote()->getQuoteIdentifier();
        $quoteCollectionTransfer = $this->quoteCollectionReaderPlugin->getQuoteCollectionByCriteria(new QuoteCriteriaFilterTransfer());
        foreach ($quoteCollectionTransfer->getQuotes() as $customerQuote) {
            if ($customerQuote->getUuid() === $quoteIdentifier) {
                return $customerQuote;
            }
        }

        return null;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateQuote(): QuoteResponseTransfer
    {
        return $this->cartClient->validateQuote();
    }

    /**
     * @return void
     */
    public function clearQuote(): void
    {
        $this->cartClient->clearQuote();
    }
}
