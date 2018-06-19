<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business;

interface ProductPackagingUnitStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all ProductPackaging by productAbstractIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $idProductAbstracts
     *
     * @return void
     */
    public function publishProductAbstractPackaging(array $idProductAbstracts): void;

    /**
     * Specification:
     * - Finds and deletes ProductPackaging storage entities by productAbstractIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array $idProductAbstracts
     *
     * @return void
     */
    public function unpublishProductAbstractPackaging(array $idProductAbstracts): void;

    /**
     *  Specification:
     * - Queries all productAbstractIds by productPackagingUnitTypeId
     *
     * @api
     *
     * @param array $productPackagingUnitTypeIds
     *
     * @return array
     */
    public function getIdProductAbstractsByIdProductPackagingUnitTypes(array $productPackagingUnitTypeIds): array;
}
