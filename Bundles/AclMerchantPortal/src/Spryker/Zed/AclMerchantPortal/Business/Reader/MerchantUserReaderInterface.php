<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Reader;

use Generator;

interface MerchantUserReaderInterface
{
    /**
     * @return \Generator<array<\Generated\Shared\Transfer\MerchantUserTransfer>>
     */
    public function getMerchantUserTransfersGenerator(): Generator;
}
