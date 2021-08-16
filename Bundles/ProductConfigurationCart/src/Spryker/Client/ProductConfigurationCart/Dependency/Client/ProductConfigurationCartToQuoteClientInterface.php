<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationCart\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

interface ProductConfigurationCartToQuoteClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote(): QuoteTransfer;
}
