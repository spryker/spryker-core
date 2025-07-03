<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Dependency\Facade;

interface CheckoutToQuoteFacadeInterface
{
    /**
     * @param int $idQuote
     *
     * @return bool
     */
    public function acquireExclusiveLockForQuote(int $idQuote): bool;
}
