<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCodeExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CartCodePluginInterface
{
    /**
     * Specification:
     * - Executed by {@link \Spryker\Zed\CartCode\Business\CartCodeFacadeInterface::addCartCode()} method.
     * - Extends QuoteTransfer with $cartCode and its relevant data when the $cartCode is applicable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addCartCode(QuoteTransfer $quoteTransfer, string $cartCode): QuoteTransfer;

    /**
     * Specification:
     * - Executed by {@link \Spryker\Zed\CartCode\Business\CartCodeFacadeInterface::removeCartCode()} method.
     * - Cleans up $cartCode and its relevant data when $cartCode is present in QuoteTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeCartCode(QuoteTransfer $quoteTransfer, string $cartCode): QuoteTransfer;

    /**
     * Specification:
     * - Executed by {@link \Spryker\Zed\CartCode\Business\CartCodeFacadeInterface::clearCartCodes()} method.
     * - Clears all codes and their relevant data when $cartCode is present in QuoteTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function clearCartCodes(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Executed after every cart code operations (add cart code, remove cart code).
     * - Checks QuoteTransfer and provides a success or an error message.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    public function findOperationResponseMessage(QuoteTransfer $quoteTransfer, string $cartCode): ?MessageTransfer;
}
