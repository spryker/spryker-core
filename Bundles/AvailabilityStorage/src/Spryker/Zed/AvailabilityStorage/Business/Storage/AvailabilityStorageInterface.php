<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Business\Storage;

interface AvailabilityStorageInterface
{

    /**
     * @param array $availabilityIds
     *
     * @return void
     */
    public function publish(array $availabilityIds);

    /**
     * @param array $availabilityIds
     *
     * @return void
     */
    public function unpublish(array $availabilityIds);
}
