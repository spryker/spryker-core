<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Client\Availability;


/**
 * @method \Spryker\Client\Availability\AvailabilityFactory getFactory()
 */
interface AvailabilityClientInterface
{

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAvailabilityByIdProductAbstract($idProductAbstract);
}
