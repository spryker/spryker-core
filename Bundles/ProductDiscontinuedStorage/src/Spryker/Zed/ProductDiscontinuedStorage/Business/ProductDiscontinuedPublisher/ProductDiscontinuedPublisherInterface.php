<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedPublisher;

interface ProductDiscontinuedPublisherInterface
{
    /**
     * @param int[] $productDiscontinuedIds
     *
     * @return void
     */
    public function publish(array $productDiscontinuedIds): void;
}
