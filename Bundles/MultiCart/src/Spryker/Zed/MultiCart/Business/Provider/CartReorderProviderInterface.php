<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Business\Provider;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CartReorderProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuoteForCartReorder(CartReorderRequestTransfer $cartReorderRequestTransfer): QuoteTransfer;
}
