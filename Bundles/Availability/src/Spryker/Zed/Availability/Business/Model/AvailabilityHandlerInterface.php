<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Generated\Shared\Transfer\StoreTransfer;

interface AvailabilityHandlerInterface
{
    /**
     * @param string $sku
     *
     * @return void
     */
    public function updateAvailability($sku);

    /**
     * @param int $idAvailabilityAbstract
     *
     * @return void
     */
    public function touchAvailabilityAbstract($idAvailabilityAbstract);

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return int
     */
    public function saveCurrentAvailability($sku, $quantity);

    /**
     * @param string $sku
     * @param int $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function saveCurrentAvailabilityForStore($sku, $quantity, StoreTransfer $storeTransfer);

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return mixed
     */
    public function updateAvailabilityForStore($sku, StoreTransfer $storeTransfer);
}
