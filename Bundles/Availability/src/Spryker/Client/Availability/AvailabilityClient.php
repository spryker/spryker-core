<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Availability;

use Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer;
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

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer $productConcreteAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailability(ProductConcreteAvailabilityRequestTransfer $productConcreteAvailabilityRequestTransfer)
    {
        return $this->getFactory()
            ->createAvailabilityStub()
            ->findProductConcreteAvailability($productConcreteAvailabilityRequestTransfer);
    }
}
