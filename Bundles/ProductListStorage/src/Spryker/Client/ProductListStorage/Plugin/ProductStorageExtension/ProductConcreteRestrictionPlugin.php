<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage\Plugin\ProductStorageExtension;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductConcreteRestrictionPluginInterface;

/**
 * @method \Spryker\Client\ProductListStorage\ProductListStorageClientInterface getClient()
 */
class ProductConcreteRestrictionPlugin extends AbstractPlugin implements ProductConcreteRestrictionPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return bool
     */
    public function isRestricted(int $idProduct): bool
    {
        return $this->getClient()->isProductConcreteRestricted($idProduct);
    }
}
