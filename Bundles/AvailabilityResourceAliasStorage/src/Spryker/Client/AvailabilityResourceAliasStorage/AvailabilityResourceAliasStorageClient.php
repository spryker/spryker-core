<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityResourceAliasStorage;

use Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\AvailabilityResourceAliasStorage\AvailabilityResourceAliasStorageFactory getFactory()
 */
class AvailabilityResourceAliasStorageClient extends AbstractClient implements AvailabilityResourceAliasStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer
     */
    public function getAvailabilityAbstract(string $sku): SpyAvailabilityAbstractEntityTransfer
    {
        return $this->getFactory()
            ->createAvailabilityStorageReader()
            ->getAvailabilityAbstract($sku);
    }
}
