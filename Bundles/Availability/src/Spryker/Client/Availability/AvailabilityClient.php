<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Availability;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Availability\AvailabilityFactory getFactory()
 */
class AvailabilityClient extends AbstractClient implements AvailabilityClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use `\Spryker\Client\AvailabilityStorage\AvailabilityStorageClient::getProductAvailabilityByIdProductAbstract() instead`.
     *
     * @param int $idProductAbstract
     *
     * @throws \Spryker\Client\Availability\Exception\ProductAvailabilityNotFoundException
     *
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer
     */
    public function getProductAvailabilityByIdProductAbstract($idProductAbstract)
    {
        return $this->getFactory()
            ->createCurrentLocaleAvailabilityStorage()
            ->getProductAvailability($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer|null
     */
    public function findProductAvailabilityByIdProductAbstract($idProductAbstract)
    {
        return $this->getFactory()
            ->createCurrentLocaleAvailabilityStorage()
            ->findProductAvailability($idProductAbstract);
    }
}
