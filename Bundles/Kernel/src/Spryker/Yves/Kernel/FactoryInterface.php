<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel;

interface FactoryInterface
{
    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return $this
     */
    public function setContainer(Container $container);
}
