<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCode\Business;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CartCodeFacadeInterface
{
    /**
     * Specification:
     * - Executes CartCodePluginInterface::addCandidate() methods of the used plugins, which can extend the QuoteTransfer
     * with any relevant data, activated by the $code string.
     * - No change will be done and the result will contain an error message when the selected cart is locked for changes.
     * - Executes quote recalculation after the quote extension.
     * - Executes CartCodePluginInterface::getCartCodeOperationResult() methods of the used plugins. Each plugin can generate
     * a success or an error message depending on the result QuoteTransfer after code activation and recalculation.
     * - The response will contain the updated and recalculated QuoteTransfer and an array of success and error messages.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, string $code): CartCodeOperationResultTransfer;

    /**
     * Specification:
     * - Executes CartCodePluginInterface::removeCode() methods of the used plugins, which can clean up the QuoteTransfer
     * by any irrelevant data, previously activated by the $code string.
     * - No change will be done and the result will contain an error message when the selected cart is locked for changes.
     * - Executes quote recalculation after the quote clean up.
     * - Executes CartCodePluginInterface::getCartCodeOperationResult() methods of the used plugins. Each plugin can generate
     * a success or an error message depending on the result QuoteTransfer after code clean up and recalculation.
     * - The response will contain the updated and recalculated QuoteTransfer and an array of success and error messages.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function removeCode(QuoteTransfer $quoteTransfer, string $code): CartCodeOperationResultTransfer;

    /**
     * Specification:
     * - Executes CartCodePluginInterface::clearAllCodes() methods of the used plugins, which can clear all relevant codes
     * and their data from the QuoteTransfer, previously activated by the a given plugin.
     * - No change will be done and the result will contain an error message when the selected cart is locked for changes.
     * - Executes quote recalculation after the quote clean up.
     * - Executes CartCodePluginInterface::getCartCodeOperationResult() methods of the used plugins. Each plugin can generate
     * a success or an error message depending on the result QuoteTransfer after code clean up and recalculation.
     * - The response will contain the updated and recalculated QuoteTransfer and an array of success and error messages.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function clearAllCodes(QuoteTransfer $quoteTransfer): CartCodeOperationResultTransfer;
}
