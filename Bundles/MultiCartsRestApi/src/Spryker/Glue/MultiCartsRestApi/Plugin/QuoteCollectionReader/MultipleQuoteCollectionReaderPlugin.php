<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiCartsRestApi\Plugin\QuoteCollectionReader;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\MultiCartsRestApi\MultiCartsRestApiFactory getFactory()
 */
class MultipleQuoteCollectionReaderPlugin extends AbstractPlugin implements QuoteCollectionReaderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Returns quote collection transfer with single quote for customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollectionByCriteria(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer
    {
        return $this->getFactory()
            ->createMultipleQuoteCollectionReader()
            ->getQuoteCollectionByCriteria($quoteCriteriaFilterTransfer);
    }
}
