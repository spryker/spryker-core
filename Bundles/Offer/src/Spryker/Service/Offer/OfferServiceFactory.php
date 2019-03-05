<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Offer;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Offer\Rounder\FloatConverter;
use Spryker\Service\Offer\Rounder\FloatConverterInterface;

/**
 * @method \Spryker\Service\Offer\OfferConfig getConfig()
 */
class OfferServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Offer\Rounder\FloatConverterInterface
     */
    public function createFloatConverter(): FloatConverterInterface
    {
        return new FloatConverter($this->getConfig());
    }
}
