<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Business;

interface PriceProductStorageFacadeInterface
{
    /**
     * @api
     *
     * @param array $productConcreteIds
     *
     * @return void
     */
    public function publishPriceProductConcrete(array $productConcreteIds);

    /**
     * @api
     *
     * @param array $productConcreteIds
     *
     * @return void
     */
    public function unpublishPriceProductConcrete(array $productConcreteIds);

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publishPriceProductAbstract(array $productAbstractIds);

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublishPriceProductAbstract(array $productAbstractIds);
}
