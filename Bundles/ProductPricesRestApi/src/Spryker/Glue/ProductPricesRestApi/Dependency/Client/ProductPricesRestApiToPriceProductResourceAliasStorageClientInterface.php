<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Dependency\Client;

interface ProductPricesRestApiToPriceProductResourceAliasStorageClientInterface
{
    /**
     * @param string $abstractProductSku
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceProductAbstractStorageTransfer($abstractProductSku);

    /**
     * @param string $concreteProductSku
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceProductConcreteStorageTransfer($concreteProductSku);
}
