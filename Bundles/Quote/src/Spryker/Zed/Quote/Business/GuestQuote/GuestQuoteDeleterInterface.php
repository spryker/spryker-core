<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\GuestQuote;

interface GuestQuoteDeleterInterface
{
    /**
     * @return void
     */
    public function deleteExpiredGuestQuote(): void;
}
