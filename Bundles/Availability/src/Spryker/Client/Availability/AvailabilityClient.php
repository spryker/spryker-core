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
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer
     *
     * @throws \Spryker\Client\Availability\Exception\ProductAvailabilityNotFoundException
     */
    public function getProductAvailabilityByIdProductAbstract($idProductAbstract)
    {
        $locale = $this->getFactory()->getLocaleClient()->getCurrentLocale();
        $availabilityStorage = $this->getFactory()->createAvailabilityStorage($locale);

        return $availabilityStorage->getProductAvailability($idProductAbstract);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function hasProductAvailabilityByIdProductAbstract($idProductAbstract)
    {
        $locale = $this->getFactory()->getLocaleClient()->getCurrentLocale();
        $availabilityStorage = $this->getFactory()->createAvailabilityStorage($locale);

        return $availabilityStorage->hasProductAvailability($idProductAbstract);
    }

}
