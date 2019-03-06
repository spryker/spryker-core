<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductBundle;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\ProductBundle\FloatConverter\FloatConverter;
use Spryker\Service\ProductBundle\FloatConverter\FloatConverterInterface;

class ProductBundleServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\ProductBundle\FloatConverter\FloatConverterInterface
     */
    public function createFloatConverter(): FloatConverterInterface
    {
        return new FloatConverter();
    }
}
