<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductLabel;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\ProductLabel\Twig\ProductLabelTwigExtension;

/**
 * @method \Spryker\Client\ProductLabel\ProductLabelClientInterface getClient()
 */
class ProductLabelFactory extends AbstractFactory
{
    /**
     * @param string $localeName
     *
     * @return \Spryker\Yves\ProductLabel\Twig\ProductLabelTwigExtension
     */
    public function createProductLabelTwigExtension($localeName)
    {
        return new ProductLabelTwigExtension(
            $this->getClient(),
            $localeName,
        );
    }
}
