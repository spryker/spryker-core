<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business;

interface ProductPackagingUnitStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all abstractProduct with the given productConcretes and ProductPackagingUnits
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $productConcreteIds
     *
     * @return void
     */
    public function publishProductAbstractPackaging(array $productConcreteIds);
}
