<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCodesRestApi;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CartCodesRestApi\CartCodesRestApiFactory getFactory()
 */
class CartCodesRestApiClient extends AbstractClient implements CartCodesRestApiClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return CartCodeOperationResultTransfer
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, string $voucherCode): CartCodeOperationResultTransfer
    {
        return $this->getFactory()->createCartCodeAdder()->addCandidate($quoteTransfer, $voucherCode);
    }
}
