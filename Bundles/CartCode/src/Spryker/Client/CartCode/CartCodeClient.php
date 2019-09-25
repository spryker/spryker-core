<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCode;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CartCode\CartCodeFactory getFactory()
 */
class CartCodeClient extends AbstractClient implements CartCodeClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, string $code): CartCodeOperationResultTransfer
    {
        return $this->getFactory()->createCodeAdder()->addCandidate($quoteTransfer, $code);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function removeCode(QuoteTransfer $quoteTransfer, string $code): CartCodeOperationResultTransfer
    {
        return $this->getFactory()->createCodeRemover()->remove($quoteTransfer, $code);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function clearAllCodes(QuoteTransfer $quoteTransfer): CartCodeOperationResultTransfer
    {
        return $this->getFactory()->createCodeClearer()->clearAllCodes($quoteTransfer);
    }
}
