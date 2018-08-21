<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityResourceAliasStorage\Persistence;

use Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorage;

interface AvailabilityResourceAliasStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorage $availabilityStorageEntity
     *
     * @return void
     */
    public function saveAvailabilityStorageEntity(SpyAvailabilityStorage $availabilityStorageEntity): void;
}
