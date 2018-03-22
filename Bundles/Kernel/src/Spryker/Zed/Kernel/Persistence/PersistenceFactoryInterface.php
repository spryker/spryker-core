<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence;

use Spryker\Zed\Kernel\Container;

interface PersistenceFactoryInterface
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    public function setContainer(Container $container);

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getProvidedDependency($key);
}
