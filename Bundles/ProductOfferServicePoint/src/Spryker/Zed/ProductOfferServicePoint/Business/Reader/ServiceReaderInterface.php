<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Reader;

use Generated\Shared\Transfer\ServiceCollectionTransfer;

interface ServiceReaderInterface
{
    /**
     * @param list<string> $serviceUuids
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionTransfer
     */
    public function getServiceCollectionByServiceUuids(array $serviceUuids): ServiceCollectionTransfer;
}
