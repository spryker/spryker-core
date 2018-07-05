<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductAbstractRestrictionPluginInterface;

/**
 * @method \Spryker\Client\ProductListStorage\ProductListStorageClientInterface getClient()
 */
class ProductAbstractRestrictionPlugin extends AbstractPlugin implements ProductAbstractRestrictionPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isRestricted(int $idProductAbstract): bool
    {
        return $this->getClient()->isProductAbstractRestricted($idProductAbstract);
    }
}
