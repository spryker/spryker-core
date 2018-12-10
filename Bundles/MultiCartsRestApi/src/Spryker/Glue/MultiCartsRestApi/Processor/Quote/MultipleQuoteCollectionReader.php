<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiCartsRestApi\Processor\Quote;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Glue\MultiCartsRestApi\Dependency\Client\MultiCartsRestApiToMultiCartClientInterface;

class MultipleQuoteCollectionReader implements MultipleQuoteCollectionReaderInterface
{
    /**
     * @var \Spryker\Glue\MultiCartsRestApi\Dependency\Client\MultiCartsRestApiToMultiCartClientInterface
     */
    protected $multiCartClient;

    /**
     * @param \Spryker\Glue\MultiCartsRestApi\Dependency\Client\MultiCartsRestApiToMultiCartClientInterface $multiCartClient
     */
    public function __construct(MultiCartsRestApiToMultiCartClientInterface $multiCartClient)
    {
        $this->multiCartClient = $multiCartClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollectionByCriteria(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer
    {
        return $this->multiCartClient->getQuoteCollectionByCriteria($quoteCriteriaFilterTransfer);
    }
}
