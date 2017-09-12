<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PyzAvailability;

use Spryker\Client\Availability\AvailabilityClient;
use Spryker\Yves\Kernel\AbstractFactory;

class PyzAvailabilityFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Availability\AvailabilityClientInterface
     */
    public function getAvailabilityClient()
    {
        return new AvailabilityClient();
    }

}
