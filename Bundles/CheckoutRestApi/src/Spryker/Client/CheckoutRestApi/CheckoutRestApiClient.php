<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CheckoutRestApi;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CheckoutRestApi\CheckoutRestApiFactory getFactory()
 */
class CheckoutRestApiClient extends AbstractClient implements CheckoutRestApiClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutDataTransfer
     */
    public function getCheckoutData(QuoteTransfer $quoteTransfer): CheckoutDataTransfer
    {
        return $this->getFactory()->createZedStub()->getCheckoutData($quoteTransfer);
    }
}
