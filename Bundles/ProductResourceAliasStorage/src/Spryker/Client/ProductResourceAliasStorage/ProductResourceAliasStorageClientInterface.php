<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductResourceAliasStorage;

interface ProductResourceAliasStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves a current Store specific ProductAbstract resource from Storage by sku.
     *
     * @api
     *
     * @param string $sku
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageData(string $sku, string $localeName): ?array;

    /**
     * Specification:
     * - Retrieves a current Store specific ProductConcrete resource from Storage by sku.
     *
     * @api
     *
     * @param string $sku
     * @param string $localeName
     *
     * @return array|null
     */
    public function getProductConcreteStorageData(string $sku, string $localeName): ?array;
}
