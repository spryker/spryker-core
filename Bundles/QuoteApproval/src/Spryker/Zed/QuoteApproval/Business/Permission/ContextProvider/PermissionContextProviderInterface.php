<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\Permission\ContextProvider;

use Generated\Shared\Transfer\QuoteTransfer;

interface PermissionContextProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function provideContext(QuoteTransfer $quoteTransfer): array;
}
