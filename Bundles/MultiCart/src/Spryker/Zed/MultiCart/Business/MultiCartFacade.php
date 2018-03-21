<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Business;

use Generated\Shared\Transfer\QuoteActivatorRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MultiCart\Business\MultiCartBusinessFactory getFactory()
 */
class MultiCartFacade extends AbstractFacade implements MultiCartFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteActivatorRequestTransfer $quoteActivatorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setDefaultQuote(QuoteActivatorRequestTransfer $quoteActivatorRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()->createQuoteActivator()->setDefaultQuote($quoteActivatorRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function expandQuoteResponse(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()->createQuoteResponseExpander()->expand($quoteResponseTransfer);
    }
}
