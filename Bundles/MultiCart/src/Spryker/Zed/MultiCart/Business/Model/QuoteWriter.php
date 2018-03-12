<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Business\Model;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface;

class QuoteWriter implements QuoteWriterInterface
{
    /**
     * @var \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(MultiCartToQuoteFacadeInterface $quoteFacade)
    {
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->quoteFacade->persistQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->requireIdQuote();
        return $this->quoteFacade->persistQuote($quoteTransfer);
    }
}
