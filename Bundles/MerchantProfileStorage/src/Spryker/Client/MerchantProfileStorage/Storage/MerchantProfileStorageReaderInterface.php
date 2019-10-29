<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProfileStorage\Storage;

use Generated\Shared\Transfer\MerchantProfileViewTransfer;

interface MerchantProfileStorageReaderInterface
{
    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantProfileViewTransfer|null
     */
    public function findMerchantProfileStorageViewData(int $idMerchant): ?MerchantProfileViewTransfer;
}
