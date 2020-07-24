<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Container;

use Spryker\Service\Container\Container;

class ContainerProxy extends Container
{
    /**
     * @param array $services
     */
    public function __construct(array $services = [])
    {
        $services += ['logger' => null, 'debug' => false, 'charset' => 'UTF-8'];

        parent::__construct($services);

        GlobalContainer::setContainer($this);
    }
}
