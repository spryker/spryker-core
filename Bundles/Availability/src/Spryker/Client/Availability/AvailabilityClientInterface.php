<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Client\Availability;

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
     * @param int $idProductAbstract
     *
     * @throws \Spryker\Client\Availability\Exception\ProductAvailabilityNotFoundException
     *
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer
     */
    public function getProductAvailabilityByIdProductAbstract($idProductAbstract);

    /**
     * Specification:
     * - Checks if product availability data exists for current locale in Storage
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function hasProductAvailabilityByIdProductAbstract($idProductAbstract);

}
