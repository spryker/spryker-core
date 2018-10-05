<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify\Locator;

use Spryker\Shared\Kernel\LocatorLocatorInterface;

abstract class AbstractLocator implements LocatorLocatorInterface
{
    /**
     * @param string $bundle
     * @param array|null $arguments
     *
     * @return \Spryker\Shared\Kernel\BundleProxy
     */
    public function __call($bundle, ?array $arguments = null)
    {
        return $this->getBundleProxy()->setBundle($bundle);
    }

    /**
     * @return \Spryker\Shared\Kernel\BundleProxy
     */
    abstract protected function getBundleProxy();
}
