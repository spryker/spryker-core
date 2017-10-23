<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Kernel\Fixtures;

use Spryker\Yves\Kernel\AbstractFactory;

class ConcreteFactory extends AbstractFactory
{
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getProvidedDependency($key)
    {
        return parent::getProvidedDependency($key);
    }
}
