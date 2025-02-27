<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Kernel\Fixtures;

use Spryker\Client\Kernel\AbstractFactory;

class KernelFactory extends AbstractFactory
{
    /**
     * @param string $key
     * @param string $fetch
     *
     * @return mixed
     */
    public function getProvidedDependency($key, $fetch = self::LOADING_EAGER)
    {
        return parent::getProvidedDependency($key, $fetch);
    }
}
