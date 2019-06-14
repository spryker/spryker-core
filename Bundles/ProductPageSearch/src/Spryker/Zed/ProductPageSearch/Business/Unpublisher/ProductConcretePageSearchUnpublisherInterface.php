<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Unpublisher;

interface ProductConcretePageSearchUnpublisherInterface
{
    /**
     * @param array $productAbstractStoreMap Keys are product abstract IDs, values are store IDs.
     *
     * @return void
     */
    public function unpublishByAbstractProductsAndStores(array $productAbstractStoreMap): void;
}
