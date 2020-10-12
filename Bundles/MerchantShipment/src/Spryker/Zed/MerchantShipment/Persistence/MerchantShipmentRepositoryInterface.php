<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipment\Persistence;

use Generated\Shared\Transfer\MerchantShipmentCollectionTransfer;

interface MerchantShipmentRepositoryInterface
{
    /**
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\MerchantShipmentCollectionTransfer
     */
    public function getMerchantShipments(string $merchantReference): MerchantShipmentCollectionTransfer;
}
