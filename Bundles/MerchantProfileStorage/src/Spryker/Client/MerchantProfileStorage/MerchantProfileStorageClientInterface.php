<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProfileStorage;

use Generated\Shared\Transfer\MerchantProfileStorageTransfer;

interface MerchantProfileStorageClientInterface
{
    /**
     * - Maps raw merchant profile storage data to transfer object.
     *
     * @api
     *
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\MerchantProfileStorageTransfer
     */
    public function mapMerchantProfileStorageData(array $data): MerchantProfileStorageTransfer;
}
