<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Business;

interface AvailabilityStorageFacadeInterface
{
    /**
     * @api
     *
     * @param array $availabilityIds
     *
     * @return void
     */
    public function publish(array $availabilityIds);

    /**
     * @api
     *
     * @param array $availabilityIds
     *
     * @return void
     */
    public function unpublish(array $availabilityIds);
}
