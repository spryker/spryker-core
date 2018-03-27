<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Persistence;

use Generated\Shared\Transfer\SpyQuoteEntityTransfer;

interface MultiCartRepositoryInterface
{
    /**
     * @param string $quoteName
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\SpyQuoteEntityTransfer
     */
    public function findCustomerQuoteByName(string $quoteName, string $customerReference): SpyQuoteEntityTransfer;
}
