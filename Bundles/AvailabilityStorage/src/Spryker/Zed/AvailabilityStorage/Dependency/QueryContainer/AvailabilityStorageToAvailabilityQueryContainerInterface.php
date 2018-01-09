<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Dependency\QueryContainer;

interface AvailabilityStorageToAvailabilityQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAllAvailabilityAbstracts();
}
