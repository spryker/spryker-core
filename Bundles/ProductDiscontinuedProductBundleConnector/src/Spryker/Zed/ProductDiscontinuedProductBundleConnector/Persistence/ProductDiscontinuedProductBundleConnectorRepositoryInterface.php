<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductBundleConnector\Persistence;

interface ProductDiscontinuedProductBundleConnectorRepositoryInterface
{
    /**
     * @param int $idProductDiscontinue
     *
     * @return int[]
     */
    public function findRelatedBundleProductsIds(int $idProductDiscontinue): array;

    /**
     * @param int $idProductDiscontinued
     *
     * @return int[]
     */
    public function getBundledProductsByProductDiscontinuedId(int $idProductDiscontinued): array;

    /**
     * @param array $productConcreteIds
     *
     * @return array
     */
    public function getDiscontinuedProductsByProductConcreteIds(array $productConcreteIds): array;
}
