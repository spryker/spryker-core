<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Reader;

use Generator;

interface MerchantReaderInterface
{
    /**
     * @return \Generator<array<\Generated\Shared\Transfer\MerchantTransfer>>
     */
    public function getMerchantTransfersGenerator(): Generator;
}
