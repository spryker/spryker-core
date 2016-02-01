<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Persistence;

use Spryker\Zed\Kernel\Container;

interface PersistenceFactoryInterface
{

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     */
    public function setContainer(Container $container);

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getProvidedDependency($key);

}
