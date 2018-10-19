<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\GuestCart;

interface GuestCartDeleterInterface
{
    /**
     * @return void
     */
    public function cleanExpiredGuestCart(): void;
}
