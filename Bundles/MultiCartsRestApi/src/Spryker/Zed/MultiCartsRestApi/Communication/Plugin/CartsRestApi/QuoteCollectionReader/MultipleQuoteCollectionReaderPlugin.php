<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCartsRestApi\Communication\Plugin\CartsRestApi\QuoteCollectionReader;

use Generated\Shared\Transfer\QuoteCollectionResponseTransfer;
use Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MultiCartsRestApi\Business\MultiCartsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiCartsRestApi\MultiCartsRestApiConfig getConfig()
 */
class MultipleQuoteCollectionReaderPlugin extends AbstractPlugin implements QuoteCollectionReaderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Returns quote collection transfer with list of quotes for customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionResponseTransfer
     */
    public function getQuoteCollection(
        RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer
    ): QuoteCollectionResponseTransfer {
        return $this->getFacade()->getCustomerQuoteCollection($restQuoteCollectionRequestTransfer);
    }
}
