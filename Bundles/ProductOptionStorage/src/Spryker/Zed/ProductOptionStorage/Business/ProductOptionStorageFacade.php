<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOptionStorage\Business\ProductOptionStorageBusinessFactory getFactory()
 */
class ProductOptionStorageFacade extends AbstractFacade implements ProductOptionStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all productOptions with the given productAbstractIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $this->getFactory()->createProductOptionStorageWriter()->publish($productAbstractIds);
    }

    /**
     * Specification:
     * - Finds and deletes productOptions storage entities with the given productAbstractIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $this->getFactory()->createProductOptionStorageWriter()->unpublish($productAbstractIds);
    }
}
