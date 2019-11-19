<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleStorage\Dependency\Client;

use Generated\Shared\Transfer\ProductConcreteImageStorageTransfer;

interface ConfigurableBundleStorageToProductImageStorageClientInterface
{
    /**
     * @param int $idProductConcrete
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductConcreteImageStorageTransfer|null
     */
    public function findProductImageConcreteStorageTransfer(int $idProductConcrete, string $locale): ?ProductConcreteImageStorageTransfer;
}
