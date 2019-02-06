<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\QuoteApproval\Plugin\Permission\ContextProvider;

use Generated\Shared\Transfer\QuoteTransfer;

interface PermissionContextProviderInterface
{
    public const CENT_AMOUNT = 'CENT_AMOUNT';
    public const STORE_NAME = 'STORE_NAME';
    public const CURRENCY_CODE = 'CURRENCY_CODE';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return array|null
     */
    public function provideContext(?QuoteTransfer $quoteTransfer): ?array;
}
