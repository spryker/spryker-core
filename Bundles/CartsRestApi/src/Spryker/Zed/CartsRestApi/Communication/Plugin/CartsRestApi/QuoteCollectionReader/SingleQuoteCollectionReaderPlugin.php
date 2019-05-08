<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Communication\Plugin\CartsRestApi\QuoteCollectionReader;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionResponseTransfer;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\CartsRestApi\CartsRestApiConfig getConfig()
 */
class SingleQuoteCollectionReaderPlugin extends AbstractPlugin implements QuoteCollectionReaderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Returns quote collection transfer with quote of customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionResponseTransfer
     */
    public function getQuoteCollection(CustomerTransfer $customerTransfer): QuoteCollectionResponseTransfer
    {
        return $this->getFacade()->findQuoteByCustomerAndStore($customerTransfer);
    }
}
