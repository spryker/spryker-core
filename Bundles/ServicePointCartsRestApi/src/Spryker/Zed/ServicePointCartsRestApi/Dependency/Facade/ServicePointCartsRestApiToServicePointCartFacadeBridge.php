<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointCartsRestApi\Dependency\Facade;

use Generated\Shared\Transfer\QuoteReplacementResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ServicePointCartsRestApiToServicePointCartFacadeBridge implements ServicePointCartsRestApiToServicePointCartFacadeInterface
{
    /**
     * @var \Spryker\Zed\ServicePointCart\Business\ServicePointCartFacadeInterface
     */
    protected $servicePointCartFacade;

    /**
     * @param \Spryker\Zed\ServicePointCart\Business\ServicePointCartFacadeInterface $servicePointCartFacade
     */
    public function __construct($servicePointCartFacade)
    {
        $this->servicePointCartFacade = $servicePointCartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteReplacementResponseTransfer
     */
    public function replaceQuoteItems(QuoteTransfer $quoteTransfer): QuoteReplacementResponseTransfer
    {
        return $this->servicePointCartFacade->replaceQuoteItems($quoteTransfer);
    }
}
