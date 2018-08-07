<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\Permission;

use Generated\Shared\Transfer\QuoteTransfer;

interface PermissionCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function getQuoteAccessLevel(QuoteTransfer $quoteTransfer): string;
}
