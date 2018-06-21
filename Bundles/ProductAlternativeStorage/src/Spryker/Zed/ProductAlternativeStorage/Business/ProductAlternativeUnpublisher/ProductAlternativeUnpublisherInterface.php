<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeUnpublisher;

interface ProductAlternativeUnpublisherInterface
{
    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function unpublish(array $productIds): void;
}
