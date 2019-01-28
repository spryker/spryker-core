<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Unpublisher;

interface ProductConcretePageSearchUnpublisherInterface
{
    /**
     * @param array $storesPerAbstractProducts - ['abstractProductId' => ['storeName1', 'storeName2']]
     *
     * @return void
     */
    public function unpublishByAbstractProductsAndStores(array $storesPerAbstractProducts): void;
}
