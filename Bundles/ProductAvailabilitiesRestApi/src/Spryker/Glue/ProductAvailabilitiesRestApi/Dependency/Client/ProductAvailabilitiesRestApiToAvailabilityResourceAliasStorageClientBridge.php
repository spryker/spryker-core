<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client;

use Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer;

class ProductAvailabilitiesRestApiToAvailabilityResourceAliasStorageClientBridge implements ProductAvailabilitiesRestApiToAvailabilityResourceAliasStorageClientInterface
{
    /**
     * @var \Spryker\Client\AvailabilityResourceAliasStorage\AvailabilityResourceAliasStorageClientInterface
     */
    protected $availabilityResourceAliasStorageClient;

    /**
     * @param \Spryker\Client\AvailabilityResourceAliasStorage\AvailabilityResourceAliasStorageClientInterface $availabilityResourceAliasStorageClient
     */
    public function __construct($availabilityResourceAliasStorageClient)
    {
        $this->availabilityResourceAliasStorageClient = $availabilityResourceAliasStorageClient;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer
     */
    public function getAvailabilityAbstract(string $sku): SpyAvailabilityAbstractEntityTransfer
    {
        return $this->availabilityResourceAliasStorageClient->getAvailabilityAbstract($sku);
    }
}
