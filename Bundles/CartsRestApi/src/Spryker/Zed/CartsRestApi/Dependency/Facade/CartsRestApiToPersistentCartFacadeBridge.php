<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Dependency\Facade;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;

class CartsRestApiToPersistentCartFacadeBridge implements CartsRestApiToPersistentCartFacadeInterface
{
    /**
     * @var \Spryker\Zed\PersistentCart\Business\PersistentCartFacadeInterface
     */
    protected $persistentCartFacade;

    /**
     * @param \Spryker\Zed\PersistentCart\Business\PersistentCartFacadeInterface $persistentCartFacade
     */
    public function __construct($persistentCartFacade)
    {
        $this->persistentCartFacade = $persistentCartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteUpdateRequestTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->persistentCartFacade->updateQuote($quoteTransfer);
    }
}
