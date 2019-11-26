<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CartVariant\Dependency\Client;

class CartVariantToAvailabilityStorageClientBridge implements CartVariantToAvailabilityStorageClientBridgeInterface
{
    /**
     * @var \Spryker\Client\AvailabilityStorage\AvailabilityStorageClientInterface
     */
    protected $availabilityStorageClient;

    /**
     * @param \Spryker\Client\AvailabilityStorage\AvailabilityStorageClientInterface $availabilityStorageClient
     */
    public function __construct($availabilityStorageClient)
    {
        $this->availabilityStorageClient = $availabilityStorageClient;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer
     */
    public function getProductAvailabilityByIdProductAbstract($idProductAbstract)
    {
        return $this->availabilityStorageClient->getProductAvailabilityByIdProductAbstract($idProductAbstract);
    }
}
