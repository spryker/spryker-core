<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

use Spryker\Shared\Kernel\Store;

class ModuleNameResolver
{
    /**
     * @var string|null
     */
    protected $store;

    /**
     * @param string $moduleName
     *
     * @return string
     */
    public function resolve(string $moduleName): string
    {
        $storeName = $this->getStoreName();

        $storeIdentifierLength = mb_strlen($storeName);
        $storeSuffix = mb_substr($moduleName, -$storeIdentifierLength);

        if ($storeSuffix === $storeName) {
            $moduleName = mb_substr($moduleName, 0, -$storeIdentifierLength);
        }

        return $moduleName;
    }

    /**
     * @return string
     */
    protected function getStoreName(): string
    {
        if ($this->store === null) {
            $this->store = Store::getInstance()->getStoreName();
        }

        return $this->store;
    }
}
