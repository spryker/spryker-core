<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductGroup;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductGroup\ProductGroupFactory getFactory()
 */
class ProductGroupClient extends AbstractClient implements ProductGroupClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array
     */
    public function findProductGroupItemsByIdProductAbstract($idProductAbstract, $localeName)
    {
        return $this->getFactory()
            ->createProductStorageReader()
            ->findProductGroupItemsByIdProductAbstract($idProductAbstract, $localeName);
    }
}
