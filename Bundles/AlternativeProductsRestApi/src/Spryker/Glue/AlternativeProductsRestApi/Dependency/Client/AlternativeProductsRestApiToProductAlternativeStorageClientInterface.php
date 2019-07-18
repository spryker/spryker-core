<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AlternativeProductsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ProductAlternativeStorageTransfer;

interface AlternativeProductsRestApiToProductAlternativeStorageClientInterface
{
    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeStorageTransfer|null
     */
    public function findProductAlternativeStorage(string $concreteSku): ?ProductAlternativeStorageTransfer;
}
