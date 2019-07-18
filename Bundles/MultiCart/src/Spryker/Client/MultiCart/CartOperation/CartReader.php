<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\CartOperation;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Client\MultiCart\Zed\MultiCartZedStubInterface;

class CartReader implements CartReaderInterface
{
    /**
     * @var \Spryker\Client\MultiCart\Zed\MultiCartZedStubInterface
     */
    protected $multiCartZedStub;

    /**
     * @param \Spryker\Client\MultiCart\Zed\MultiCartZedStubInterface $multiCartZedStub
     */
    public function __construct(MultiCartZedStubInterface $multiCartZedStub)
    {
        $this->multiCartZedStub = $multiCartZedStub;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollectionByCriteria(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer
    {
        return $this->multiCartZedStub->getQuoteCollectionByCriteria($quoteCriteriaFilterTransfer);
    }
}
