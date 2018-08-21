<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityResourceAliasStorage\Persistence;

interface AvailabilityResourceAliasStorageRepositoryInterface
{
    /**
     * @param int[] $availabilityIds
     *
     * @return \Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorage[]
     */
    public function getAvailabilityStorageEntities(array $availabilityIds): array;

    /**
     * @param int[] $availabilityIds
     *
     * @return string[]
     */
    public function getProductAbstractSkuList(array $availabilityIds): array;
}
