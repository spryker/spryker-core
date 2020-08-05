<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Container;

use Spryker\Service\Container\Container;
use Spryker\Shared\Kernel\ContainerInterface;

abstract class AbstractApplicationContainer extends Container implements ContainerInterface
{
    /**
     * @var \Spryker\Shared\Kernel\Container\GlobalContainer|null
     */
    protected $staticContainer;

    /**
     * @param string $id
     *
     * @return bool
     */
    public function hasApplicationService(string $id): bool
    {
        return $this->getGlobalContainer()->has($id);
    }

    /**
     * @param string $id
     *
     * @return mixed
     */
    public function getApplicationService(string $id)
    {
        return $this->getGlobalContainer()->get($id);
    }

    /**
     * @return \Spryker\Shared\Kernel\Container\GlobalContainerInterface
     */
    protected function getGlobalContainer(): GlobalContainerInterface
    {
        if ($this->staticContainer === null) {
            $this->staticContainer = new GlobalContainer();
        }

        return $this->staticContainer;
    }
}
