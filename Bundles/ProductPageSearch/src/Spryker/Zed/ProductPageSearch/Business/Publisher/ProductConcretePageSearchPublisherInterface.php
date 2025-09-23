<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Publisher;

interface ProductConcretePageSearchPublisherInterface
{
    /**
     * @param array<int, int> $productIdTimestampMap
     *
     * @return void
     */
    public function publishWithTimestamp(array $productIdTimestampMap): void;

    /**
     * @param array<int> $productIds
     *
     * @return void
     */
    public function publish(array $productIds): void;

    /**
     * @param array<int, int> $productIdTimestampMap
     *
     * @return void
     */
    public function unpublishWithTimestamp(array $productIdTimestampMap): void;

    /**
     * @param array<int> $productIds
     *
     * @return void
     */
    public function unpublish(array $productIds): void;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function publishProductConcretePageSearchesByProductAbstractIds(array $productAbstractIds): void;
}
