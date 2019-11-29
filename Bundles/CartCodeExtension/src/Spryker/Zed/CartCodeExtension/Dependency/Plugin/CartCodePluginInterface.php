<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCodeExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\CartCodeResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;

interface CartCodePluginInterface
{
    /**
     * Specification:
     * - Executed by CartCodeClient::addCartCode() method.
     * - Extends QuoteTransfer with $cartCode and its relevant data when the $cartCode is applicable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function addCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer;

    /**
     * Specification:
     * - Executed by CartCodeClient::removeCartCode() method.
     * - Cleans up $cartCode and its relevant data when $cartCode is present in QuoteTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function removeCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer;

    /**
     * Specification:
     * - Executed by CartCodeClient::clearAllCartCodes() method.
     * - Clears all codes and their relevant data when $cartCode is present in QuoteTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function clearCartCodes(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer;

    /**
     * Specification:
     * - Executed after every cart code operations (add cart code, remove cart code, clear cart codes).
     * - Runs only on a recalculated QuoteTransfer.
     * - Checks QuoteTransfer and provides a success or an error message.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    public function getOperationResponseMessage(CartCodeRequestTransfer $cartCodeRequestTransfer): ?MessageTransfer;
}
