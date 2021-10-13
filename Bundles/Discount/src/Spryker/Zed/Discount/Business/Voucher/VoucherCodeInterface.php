<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Voucher;

interface VoucherCodeInterface
{
    /**
     * @param array<string> $codes
     *
     * @return int
     */
    public function releaseUsedCodes(array $codes);

    /**
     * @param array<string> $codes
     *
     * @return int
     */
    public function useCodes(array $codes);
}
