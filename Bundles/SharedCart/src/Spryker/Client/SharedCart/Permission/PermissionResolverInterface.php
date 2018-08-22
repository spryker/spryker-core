<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\Permission;

use Generated\Shared\Transfer\QuoteTransfer;

interface PermissionResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null Key of access group or null if permissions can not be applied.
     */
    public function getQuoteAccessLevel(QuoteTransfer $quoteTransfer): ?string;
}
