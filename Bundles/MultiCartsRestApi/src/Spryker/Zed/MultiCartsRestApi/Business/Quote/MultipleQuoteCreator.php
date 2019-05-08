<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCartsRestApi\Business\Quote;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\MultiCartsRestApi\Dependency\Facade\MultiCartsRestApiToPersistentCartFacadeInterface;

class MultipleQuoteCreator implements MultipleQuoteCreatorInterface
{
    /**
     * @var \Spryker\Zed\MultiCartsRestApi\Dependency\Facade\MultiCartsRestApiToPersistentCartFacadeInterface
     */
    protected $persistentCartFacade;

    /**
     * @param \Spryker\Zed\MultiCartsRestApi\Dependency\Facade\MultiCartsRestApiToPersistentCartFacadeInterface $persistentCartFacade
     */
    public function __construct(
        MultiCartsRestApiToPersistentCartFacadeInterface $persistentCartFacade
    ) {
        $this->persistentCartFacade = $persistentCartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->persistentCartFacade->createQuote($quoteTransfer);
    }
}
