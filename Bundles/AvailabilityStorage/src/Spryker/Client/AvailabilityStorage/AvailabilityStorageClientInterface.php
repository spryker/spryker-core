<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityStorage;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;

interface AvailabilityStorageClientInterface
{
    /**
     * Specification:
     *  - Return storage availability data by abstract product id.
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
     * - Return product abstract available item by abstract product id
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer|null
     */
    public function getProductAbstractAvailability(int $idProductAbstract): ?ProductAbstractAvailabilityTransfer;

    /**
     * Specification:
     *  - Return entity availability data by abstract product id.
     *
     * @api
     *
     * @deprecated Use getProductAvailabilityByIdProductAbstract() instead
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer
     */
    public function getAvailabilityAbstract($idProductAbstract);
}
