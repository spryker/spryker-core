<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume\Dependency\External;

use Symfony\Component\Validator\Validator\ValidatorInterface;

interface PriceProductOfferVolumeToValidationAdapterInterface
{
    /**
     * @return \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    public function createValidator(): ValidatorInterface;
}
