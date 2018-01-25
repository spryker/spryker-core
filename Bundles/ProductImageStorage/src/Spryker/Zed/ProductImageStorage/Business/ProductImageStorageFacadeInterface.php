<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Business;

interface ProductImageStorageFacadeInterface
{
    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publishProductAbstractImages(array $productAbstractIds);

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductAbstractImages(array $productAbstractIds);

    /**
     * @api
     *
     * @param array $productIds
     *
     * @return void
     */
    public function publishProductConcreteImages(array $productIds);

    /**
     * @api
     *
     * @param array $productIds
     *
     * @return void
     */
    public function unpublishProductConcreteImages(array $productIds);
}
