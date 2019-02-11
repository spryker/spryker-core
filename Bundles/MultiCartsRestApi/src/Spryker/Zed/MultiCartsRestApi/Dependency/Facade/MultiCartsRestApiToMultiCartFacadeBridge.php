<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCartsRestApi\Dependency\Facade;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;

class MultiCartsRestApiToMultiCartFacadeBridge implements MultiCartsRestApiToMultiCartFacadeInterface
{
    /**
     * @var \Spryker\Zed\MultiCart\Business\MultiCartFacadeInterface
     */
    protected $multiCartFacade;

    /**
     * @param \Spryker\Zed\MultiCart\Business\MultiCartFacadeInterface $multiCartFacade
     */
    public function __construct($multiCartFacade)
    {
        $this->multiCartFacade = $multiCartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollectionByCriteria(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer
    {
        return $this->multiCartFacade->getQuoteCollectionByCriteria($quoteCriteriaFilterTransfer);
    }
}
