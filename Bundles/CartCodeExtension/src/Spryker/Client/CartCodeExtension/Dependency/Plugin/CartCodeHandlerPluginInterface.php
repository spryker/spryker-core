<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCodeExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartCodeOperationMessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CartCodeHandlerPluginInterface
{
    /**
     * Specification:
     * - Executed by CartCodeClient::addCode() method.
     * - Extends QuoteTransfer with $code and its relevant data when the $code is applicable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, $code): QuoteTransfer;

    /**
     * Specification:
     * - Executed by CartCodeClient::removeCode() method.
     * - Cleans up $code and its relevant data when $code is present in QuoteTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeCode(QuoteTransfer $quoteTransfer, $code): QuoteTransfer;

    /**
     * Specification:
     * - Executed by CartCodeClient::clearAllCodes() method.
     * - Clears all codes and their relevant data when $code is present in QuoteTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function clearAllCodes(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Executed after any cart code operations on a recalculated QuoteTransfer.
     * - Checks QuoteTransfer and provides a success or an error message when necessary.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationMessageTransfer
     */
    public function getCartCodeOperationResult(QuoteTransfer $quoteTransfer, $code): CartCodeOperationMessageTransfer;
}
