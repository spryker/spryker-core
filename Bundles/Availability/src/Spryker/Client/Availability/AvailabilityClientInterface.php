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

}
