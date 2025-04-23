<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer;
use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

interface AvailabilityHandlerInterface
{
    /**
     * @param string $concreteSku
     *
     * @return void
     */
    public function updateAvailability(string $concreteSku): void;

    /**
     * @param int $idAvailabilityAbstract
     *
     * @return void
     */
    public function touchAvailabilityAbstract($idAvailabilityAbstract);

    /**
     * @param string $concreteSku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function saveAndTouchAvailability(string $concreteSku, Decimal $quantity, StoreTransfer $storeTransfer): int;

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer
     */
    public function updateProductConcreteAvailabilityById(
        int $idProductConcrete,
        StoreTransfer $storeTransfer
    ): ProductConcreteAvailabilityTransfer;

    /**
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer
     */
    public function updateProductConcreteAvailabilityBySku(
        string $concreteSku,
        StoreTransfer $storeTransfer
    ): ProductConcreteAvailabilityTransfer;

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    public function updateProductAbstractAvailabilityBySku(
        string $abstractSku,
        StoreTransfer $storeTransfer
    ): ProductAbstractAvailabilityTransfer;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function updateAvailabilityByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer;
}
