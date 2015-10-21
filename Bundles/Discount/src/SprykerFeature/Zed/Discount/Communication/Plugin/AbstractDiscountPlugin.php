<?php

namespace SprykerFeature\Zed\Discount\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

class AbstractDiscountPlugin extends AbstractPlugin
{

    /**
     * @param string $value
     *
     * @return string
     */
    public function transformForPersistence($value)
    {
        return $value;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function transformFromPersistence($value)
    {
        return $value;
    }
}
