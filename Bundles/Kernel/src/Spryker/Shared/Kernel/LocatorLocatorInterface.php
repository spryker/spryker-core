<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel;

interface LocatorLocatorInterface
{
    /**
     * @param string $bundle
     * @param array|null $arguments
     *
     * @return \Spryker\Shared\Kernel\BundleProxy
     */
    public function __call($bundle, ?array $arguments = null);
}
