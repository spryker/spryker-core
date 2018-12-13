<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel;

use ArrayAccess;

interface ContainerInterface extends ArrayAccess
{
    /**
     * @return \Spryker\Shared\Kernel\LocatorLocatorInterface
     */
    public function getLocator();
}
