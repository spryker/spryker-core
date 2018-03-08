<?php

namespace Spryker\Zed\CustomerAccess;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CustomerAccessConfig extends AbstractBundleConfig
{
    const CONTENT_TYPE_PRICE = 'price';

    /**
     * @return array
     */
    public function getDefaultContentTypes()
    {
        return [];
    }

    /**
     * @return bool
     */
    public function getDefaultContentTypeAccess()
    {
        return false;
    }
}