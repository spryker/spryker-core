<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client;

use Generated\Shared\Transfer\TaxProductStorageTransfer;

interface ProductTaxSetsRestApiToTaxProductStorageClientInterface
{
    /**
     * @param string $productAbstractSku
     *
     * @return \Generated\Shared\Transfer\TaxProductStorageTransfer|null
     */
    public function findTaxProductStorageByProductAbstractSku(string $productAbstractSku): ?TaxProductStorageTransfer;
}
