<?php

namespace Spryker\Zed\CustomerAccessStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CustomerAccessStorageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue()
    {
        return true;
    }
}
