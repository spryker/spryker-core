<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CartVariant\Dependency\Client;

class CartVariantToAvailabilityClientBridge implements CartVariantToAvailabilityClientBridgeInterface
{

    /**
     * @var \Spryker\Client\Availability\AvailabilityClientInterface
     */
    protected $client;

    /**
     * @param \Spryker\Client\Availability\AvailabilityClientInterface $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer
     */
    public function getProductAvailabilityByIdProductAbstract($idProductAbstract)
    {
        return $this->client->getProductAvailabilityByIdProductAbstract($idProductAbstract);
    }

}
