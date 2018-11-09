<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartsRestApi;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CartsRestApi\CartsRestApiFactory getFactory()
 */
class CartsRestApiClient extends AbstractClient implements CartsRestApiClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $uuidQuote
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findQuoteByIdentifier(string $uuidQuote, QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): ?QuoteTransfer
    {
        return $this->getFactory()
            ->createCartReader()
            ->findQuoteByIdentifier($uuidQuote, $quoteCriteriaFilterTransfer);
    }

    /**
     * {@inheritdoc}
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
            ->createCartReader()
            ->getQuoteCollectionByCriteria($quoteCriteriaFilterTransfer);
    }
}
