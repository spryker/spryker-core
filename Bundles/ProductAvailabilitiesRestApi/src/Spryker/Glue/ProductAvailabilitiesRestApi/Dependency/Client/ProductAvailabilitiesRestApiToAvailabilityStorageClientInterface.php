<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;

interface ProductAvailabilitiesRestApiToAvailabilityStorageClientInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer|null
     */
    public function findProductAbstractAvailability(int $idProductAbstract): ?ProductAbstractAvailabilityTransfer;
}
