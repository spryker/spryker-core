<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Publisher;

interface ProductConcretePageSearchPublisherInterface
{
    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function publish(array $productIds): void;

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function unpublish(array $productIds): void;

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publishProductConcretePageSearchesByProductAbstractIds(array $productAbstractIds): void;
}
