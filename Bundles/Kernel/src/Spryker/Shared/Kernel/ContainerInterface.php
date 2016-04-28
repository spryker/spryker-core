<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel;

interface ContainerInterface extends \ArrayAccess
{

    /**
     * @return \Spryker\Shared\Kernel\LocatorLocatorInterface
     */
    public function getLocator();

    /**
     * @param string $id
     * @param callable $callable A service definition to extend the original
     *
     * @return \Closure
     */
    public function extend($id, $callable);

}
