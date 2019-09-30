<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage\Plugin\ProductStorageExtension;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductConcreteRestrictionFilterPluginInterface;

/**
 * @method \Spryker\Client\ProductListStorage\ProductListStorageClientInterface getClient()
 */
class ProductConcreteListStorageRestrictionFilterPlugin extends AbstractPlugin implements ProductConcreteRestrictionFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters concrete product ids based on white and blacklists.
     *
     * @api
     *
     * @param int[] $productConcreteIds
     *
     * @return int[]
     */
    public function filter(array $productConcreteIds): array
    {
        return $this->getClient()->filterRestrictedConcreteProducts($productConcreteIds);
    }
}
