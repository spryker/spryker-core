<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Testify\Locator;

use Spryker\Shared\Kernel\ContainerInterface;

interface TestifyConfiguratorInterface
{
    /**
     * @return \Spryker\Shared\Kernel\ContainerInterface
     */
    public function getContainer();

    /**
     * @param \Spryker\Shared\Kernel\ContainerInterface $container
     *
     * @return $this
     */
    public function setContainer(ContainerInterface $container);

    /**
     * @return \Spryker\Shared\Testify\Config\TestifyConfigInterface
     */
    public function getConfig();
}
