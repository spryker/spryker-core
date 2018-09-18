<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Publisher;

interface ProductConcretePageSearchPublisherInterface
{
    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function publish(array $productConcreteIds): void;

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function unpublish(array $productConcreteIds): void;
}
