<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PyzProductOption;

use Spryker\Client\ProductOption\ProductOptionClient;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Kernel\Plugin\Pimple;

class PyzProductOptionFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\ProductOption\ProductOptionClient
     */
    public function getProductOptionClient()
    {
        return new ProductOptionClient();
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication()
    {
        return (new Pimple())->getApplication();
    }

}
