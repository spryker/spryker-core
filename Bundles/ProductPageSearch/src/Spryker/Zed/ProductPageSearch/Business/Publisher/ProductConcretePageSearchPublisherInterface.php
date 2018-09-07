<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Publisher;

interface ProductConcretePageSearchPublisherInterface
{
    /**
     * @param int[] $ids
     *
     * @return void
     */
    public function publish(array $ids): void;

    /**
     * @param int[] $ids
     *
     * @return void
     */
    public function unpublish(array $ids): void;
}
