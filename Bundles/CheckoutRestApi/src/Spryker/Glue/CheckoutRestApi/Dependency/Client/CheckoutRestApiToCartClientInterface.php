<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Dependency\Client;

interface CheckoutRestApiToCartClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote();

    /**
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateQuote();
}
