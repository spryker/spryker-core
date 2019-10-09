<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Availability;

use Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer;

/**
 * @method \Spryker\Client\Availability\AvailabilityFactory getFactory()
 */
interface AvailabilityClientInterface
{
    /**
     * Specification:
     * - Reads product availability data for current locale, from current Yves storage provider
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int $idProductAbstract
     *
     * @throws \Spryker\Client\Availability\Exception\ProductAvailabilityNotFoundException
     *
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer
     */
    public function getProductAvailabilityByIdProductAbstract($idProductAbstract);

    /**
     * Specification:
     * - Reads product availability data for current locale, from current Yves storage provider.
     * - Returns null if data was not found in Storage.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer|null
     */
    public function findProductAvailabilityByIdProductAbstract($idProductAbstract);

    /**
     * Specification:
     *  - Reads product concrete availability as it's persisted in Zed database
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer $productConcreteAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailability(ProductConcreteAvailabilityRequestTransfer $productConcreteAvailabilityRequestTransfer);
}
