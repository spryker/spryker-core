<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Communication\Plugin\MultiCartsRestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MultiCartsRestApiExtension\Dependency\Plugin\QuoteCollectionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\SharedCartsRestApi\SharedCartsRestApiConfig getConfig()
 * @method \Spryker\Zed\SharedCartsRestApi\Business\SharedCartsRestApiFacade getFacade()
 * @method \Spryker\Zed\SharedCartsRestApi\Communication\SharedCartsRestApiCommunicationFactory getFactory()
 */
class SharedCartQuoteCollectionExpanderPlugin extends AbstractPlugin implements QuoteCollectionExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Extends quotes collection response with customer's shared quotes collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function expandQuoteCollection(
        CustomerTransfer $customerTransfer,
        QuoteCollectionTransfer $quoteCollectionTransfer
    ): QuoteCollectionTransfer {
        return $this->getFactory()
            ->createSharedCartQuoteCollectionExpander()
            ->expandQuoteCollectionWithCustomerSharedQuoteCollection($customerTransfer, $quoteCollectionTransfer);
    }
}
