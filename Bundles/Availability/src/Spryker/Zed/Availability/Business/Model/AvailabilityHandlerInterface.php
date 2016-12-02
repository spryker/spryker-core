<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

interface AvailabilityHandlerInterface
{

    /**
     * @param string $sku
     *
     * @return void
     */
    public function updateAvailability($sku);

    /**
     * @param int
     *
     * @return void
     */
    public function touchAvailabilityAbstract($idAvailabilityAbstract);

}
