<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCodeExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface CartCodeHandlerPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return void
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, $code);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return bool
     */
    public function hasCandidate(QuoteTransfer $quoteTransfer, $code);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return void
     */
    public function removeCode(QuoteTransfer $quoteTransfer, $code);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\CartCodeCalculationResultTransfer
     * TODO: why we need this?
     */
    public function getCartCodeRecalculationResult(QuoteTransfer $quoteTransfer, $code);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return string
     * TODO: why we need this?
     */
    public function getSuccessMessage(QuoteTransfer $quoteTransfer, $code);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function clearQuote(QuoteTransfer $quoteTransfer);
}
