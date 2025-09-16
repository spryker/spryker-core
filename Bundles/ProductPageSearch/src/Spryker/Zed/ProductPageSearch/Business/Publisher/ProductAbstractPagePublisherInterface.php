<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Publisher;

interface ProductAbstractPagePublisherInterface
{
    /**
     * @param array<int, int> $productAbstractIdTimestampMap
     *
     * @return void
     */
    public function publishWithTimestamps(array $productAbstractIdTimestampMap): void;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds);

    /**
     * @param array<int> $productAbstractIds
     * @param array<string> $pageDataExpanderPluginNames
     *
     * @return void
     */
    public function refresh(array $productAbstractIds, array $pageDataExpanderPluginNames = []);

    /**
     * @param array<int, int> $productAbstractIdTimestampMap
     *
     * @return void
     */
    public function unpublishWithTimestamps(array $productAbstractIdTimestampMap);

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds);
}
