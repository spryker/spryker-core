<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Reader;

use Generated\Shared\Transfer\ProductConcreteStorageTransfer;

interface ProductConcreteStorageReaderInterface
{
    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteStorageTransfer|null
     */
    public function findProductConcreteStorageBySku(string $sku): ?ProductConcreteStorageTransfer;
}
