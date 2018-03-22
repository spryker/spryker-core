<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

use Spryker\Shared\Kernel\Store;

class BundleNameResolver
{
    /**
     * @param string $bundleName
     *
     * @return string
     */
    public function resolve($bundleName)
    {
        $storeIdentifierLength = mb_strlen($this->getStoreName());
        $storeSuffix = mb_substr($bundleName, -$storeIdentifierLength);

        if ($storeSuffix === $this->getStoreName()) {
            $bundleName = mb_substr($bundleName, 0, -$storeIdentifierLength);
        }

        return $bundleName;
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return Store::getInstance()->getStoreName();
    }
}
