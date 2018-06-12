<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

interface ProductReaderInterface
{
    /**
     * @param string $sku
     * @param null|int $limit
     *
     * @return array
     */
    public function getProductAbstractDataBySku(string $sku, ?int $limit = null): array;

    /**
     * @param string $localizedName
     * @param null|int $limit
     *
     * @return array
     */
    public function getProductAbstractDataByLocalizedName(string $localizedName, ?int $limit = null): array;

    /**
     * @param string $sku
     * @param null|int $limit
     *
     * @return array
     */
    public function getProductConcreteDataBySku(string $sku, ?int $limit = null): array;

    /**
     * @param string $localizedName
     * @param null|int $limit
     *
     * @return array
     */
    public function getProductConcreteDataByLocalizedName(string $localizedName, ?int $limit = null): array;
}
